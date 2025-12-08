<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardIndex extends Component
{
    public $startDate;
    public $endDate;
    public $filterCabang = [];
    public $filterSales = [];

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    public function updated($propertyName) 
    { 
        $this->dispatch('update-charts', data: $this->getChartData());
    }

    public function render()
    {
        // 1. KPI CARDS (Summary Global)
        $salesSum = $this->queryPenjualan()->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $returSum = $this->queryRetur()->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $arSum    = $this->queryAR()->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));
        $collSum  = $this->queryCollection()->sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));
        $persenRetur = $salesSum > 0 ? ($returSum / $salesSum) * 100 : 0;

        // 2. TOP 10 (Tetap ada)
        $topProduk = $this->queryPenjualan()
            ->selectRaw("nama_item, SUM(CAST(qty AS DECIMAL(20,2))) as total_qty")
            ->groupBy('nama_item')->orderByDesc('total_qty')->limit(10)->get();

        $topCustomer = $this->queryPenjualan()
            ->selectRaw("nama_pelanggan, SUM(CAST(total_grand AS DECIMAL(20,2))) as total_beli")
            ->groupBy('nama_pelanggan')->orderByDesc('total_beli')->limit(10)->get();

        // 3. OPSI FILTER
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        // Data Grafik
        $chartData = $this->getChartData();

        return view('livewire.dashboard-index', compact(
            'salesSum', 'returSum', 'arSum', 'collSum', 'persenRetur',
            'topProduk', 'topCustomer', 'optCabang', 'optSales', 'chartData'
        ))->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    // --- LOGIC DATA GRAFIK ---
    private function getChartData()
    {
        $dates = $this->getDatesRange();

        // A. Grafik Harian (Trend)
        $dailySales = $this->queryPenjualan()->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();
        $dailyRetur = $this->queryRetur()->selectRaw("DATE_FORMAT(tgl_retur, '%Y-%m-%d') as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();
        $dailyAR    = $this->queryAR()->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(total_nilai AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();
        $dailyColl  = $this->queryCollection()->selectRaw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tgl, SUM(CAST(receive_amount AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        $dataSales = []; $dataRetur = []; $dataAR = []; $dataColl = [];
        foreach ($dates as $date) {
            $dataSales[] = (float)($dailySales[$date] ?? 0);
            $dataRetur[] = (float)($dailyRetur[$date] ?? 0);
            $dataAR[]    = (float)($dailyAR[$date] ?? 0);
            $dataColl[]  = (float)($dailyColl[$date] ?? 0);
        }

        // B. Grafik Kinerja Sales (BARU)
        $salesPerf = $this->getSalesmanPerformance();

        return [
            // Trend
            'dates' => $dates,
            'sales' => $dataSales,
            'retur' => $dataRetur,
            'ar'    => $dataAR,
            'coll'  => $dataColl,
            // Top 10 (Untuk dikirim ke JS jika perlu, meski biasanya reload)
            // Sales Performance Arrays (Nama, Target IMS, Real IMS, dll)
            'salesNames'    => $salesPerf['names'],
            'salesTargetIMS'=> $salesPerf['target_ims'],
            'salesRealIMS'  => $salesPerf['real_ims'],
            'salesTargetOA' => $salesPerf['target_oa'],
            'salesRealOA'   => $salesPerf['real_oa'],
            'salesARLancar' => $salesPerf['ar_lancar'],
            'salesARMacet'  => $salesPerf['ar_macet'],
        ];
    }

    // --- LOGIC HITUNG KINERJA PER SALES ---
    private function getSalesmanPerformance()
    {
        // 1. Ambil Sales yang aktif di periode ini (yang ada transaksi atau target)
        // Kita ambil dari Master Sales saja agar semua muncul meski belum jualan
        $q = Sales::query();
        if(!empty($this->filterCabang)) $q->whereIn('city', $this->filterCabang);
        // Limit top 20 sales agar grafik tidak kepanjangan jika sales ribuan
        $listSales = $q->orderBy('sales_name')->take(20)->get();

        $names = [];
        $targetIMS = []; $realIMS = [];
        $targetOA = [];  $realOA = [];
        $arLancar = [];  $arMacet = [];

        // Parse periode untuk ambil target bulanan
        $start = Carbon::parse($this->startDate);
        $end   = Carbon::parse($this->endDate);
        
        // Ambil Realisasi Group By Sales (Sekali Query)
        $realSales = Penjualan::selectRaw("sales_name, SUM(CAST(total_grand AS DECIMAL(20,2))) as total, COUNT(DISTINCT kode_pelanggan) as oa")
            ->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate])
            ->groupBy('sales_name')
            ->get()->keyBy('sales_name');

        $realAR = AccountReceivable::selectRaw("sales_name, 
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) <= 30 THEN CAST(nilai AS DECIMAL(20,2)) ELSE 0 END) as lancar,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) > 30 THEN CAST(nilai AS DECIMAL(20,2)) ELSE 0 END) as macet")
            ->where('nilai', '>', 0)
            ->groupBy('sales_name')
            ->get()->keyBy('sales_name');

        // Ambil Target (Kita ambil target bulan Start Date saja sebagai patokan)
        $targets = SalesTarget::where('year', $start->year)
            ->where('month', $start->month)
            ->get()->keyBy('sales_id');

        foreach($listSales as $s) {
            $names[] = $s->sales_name;
            
            // Target
            $t = $targets->get($s->id);
            $targetIMS[] = $t ? (float)$t->target_ims : 0;
            $targetOA[]  = $t ? (int)$t->target_oa : 0;

            // Realisasi Sales & OA
            $r = $realSales->get($s->sales_name);
            $realIMS[] = $r ? (float)$r->total : 0;
            $realOA[]  = $r ? (int)$r->oa : 0;

            // Realisasi AR
            $a = $realAR->get($s->sales_name);
            $arLancar[] = $a ? (float)$a->lancar : 0;
            $arMacet[]  = $a ? (float)$a->macet : 0;
        }

        return [
            'names' => $names,
            'target_ims' => $targetIMS, 'real_ims' => $realIMS,
            'target_oa' => $targetOA,   'real_oa' => $realOA,
            'ar_lancar' => $arLancar,   'ar_macet' => $arMacet
        ];
    }

    private function baseFilter($query, $dateCol) {
        return $query->whereDate($dateCol, '>=', $this->startDate)
                     ->whereDate($dateCol, '<=', $this->endDate)
                     ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
                     ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales));
    }
    private function queryPenjualan() { return $this->baseFilter(Penjualan::query(), 'tgl_penjualan'); }
    private function queryRetur()     { return $this->baseFilter(Retur::query(), 'tgl_retur'); }
    private function queryAR()        { return $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan'); }
    private function queryCollection(){ 
        $q = Collection::query()->whereDate('tanggal', '>=', $this->startDate)->whereDate('tanggal', '<=', $this->endDate)
                ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang));
        if(!empty($this->filterSales)) $q->whereIn('sales_name', $this->filterSales);
        return $q;
    }
    private function getDatesRange() {
        $dates = []; $c = Carbon::parse($this->startDate); $e = Carbon::parse($this->endDate);
        while ($c <= $e) { $dates[] = $c->format('Y-m-d'); $c->addDay(); }
        return $dates;
    }
}