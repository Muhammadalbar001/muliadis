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
        // 1. KPI
        $salesSum = $this->queryPenjualan()->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $returSum = $this->queryRetur()->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $arSum    = $this->queryAR()->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));
        $collSum  = $this->queryCollection()->sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));
        $persenRetur = $salesSum > 0 ? ($returSum / $salesSum) * 100 : 0;

        // 2. TOP RANKING
        $topProduk = $this->queryPenjualan()
            ->selectRaw("nama_item, SUM(CAST(qty AS DECIMAL(20,2))) as total_qty")
            ->groupBy('nama_item')->orderByDesc('total_qty')->limit(10)->get();

        $topCustomer = $this->queryPenjualan()
            ->selectRaw("nama_pelanggan, SUM(CAST(total_grand AS DECIMAL(20,2))) as total_beli")
            ->groupBy('nama_pelanggan')->orderByDesc('total_beli')->limit(10)->get();

        $topSupplier = $this->queryPenjualan()
            ->selectRaw("supplier, SUM(CAST(total_grand AS DECIMAL(20,2))) as total_beli")
            ->groupBy('supplier')->orderByDesc('total_beli')->limit(10)->get();

        // 3. FILTER
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        $chartData = $this->getChartData();

        // ===============================
        //     🟢 BARU: Mapping Ranking
        // ===============================
        $topProductLabels = $topProduk->pluck('nama_item')->toArray();   // BARU
        $topProductData   = $topProduk->pluck('total_qty')->toArray();   // BARU

        $topCustomerLabels = $topCustomer->pluck('nama_pelanggan')->toArray(); // BARU (optional chart)
        $topCustomerData   = $topCustomer->pluck('total_beli')->toArray();     // BARU

        $topSupplierLabels = $topSupplier->pluck('supplier')->toArray(); // BARU (optional chart)
        $topSupplierData   = $topSupplier->pluck('total_beli')->toArray(); // BARU
        // ===============================

        return view('livewire.dashboard-index', compact(
            'salesSum', 'returSum', 'arSum', 'collSum', 'persenRetur',
            'topProduk', 'topCustomer', 'topSupplier',
            'optCabang', 'optSales', 'chartData',

            // BARU: data untuk grafik ranking
            'topProductLabels', 'topProductData',  
            'topCustomerLabels', 'topCustomerData',  
            'topSupplierLabels', 'topSupplierData'
        ))->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    private function getChartData()
    {
        $dates = $this->getDatesRange();

        // A. TREND HARIAN
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

        // B. SALESMAN PERFORMANCE & SUPPLIER BREAKDOWN
        $salesPerf = $this->getSalesmanPerformance();

        return [
            'dates' => $dates,
            'sales' => $dataSales, 'retur' => $dataRetur, 'ar' => $dataAR, 'coll' => $dataColl,
            
            'salesNames'    => $salesPerf['names'],
            'salesTargetIMS'=> $salesPerf['target_ims'], 'salesRealIMS' => $salesPerf['real_ims'],
            'salesTargetOA' => $salesPerf['target_oa'],  'salesRealOA'  => $salesPerf['real_oa'],
            'salesARLancar' => $salesPerf['ar_lancar'],  'salesARMacet' => $salesPerf['ar_macet'],

            'salesSuppSeries' => $salesPerf['supp_series'], 
        ];
    }

    private function getSalesmanPerformance()
    {
        $q = Sales::query();
        if(!empty($this->filterCabang)) $q->whereIn('city', $this->filterCabang);

        $listSales = $q->orderBy('sales_name')->take(20)->get();
        $salesNamesList = $listSales->pluck('sales_name')->toArray();

        $names = []; $targetIMS = []; $realIMS = []; $targetOA = []; $realOA = []; $arLancar = []; $arMacet = [];
        
        $start = Carbon::parse($this->startDate);
        $realSales = Penjualan::selectRaw("sales_name, SUM(CAST(total_grand AS DECIMAL(20,2))) as total, COUNT(DISTINCT kode_pelanggan) as oa")->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate])->groupBy('sales_name')->get()->keyBy('sales_name');
        $realAR = AccountReceivable::selectRaw("sales_name, SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) <= 30 THEN CAST(nilai AS DECIMAL(20,2)) ELSE 0 END) as lancar, SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) > 30 THEN CAST(nilai AS DECIMAL(20,2)) ELSE 0 END) as macet")->where('nilai', '>', 0)->groupBy('sales_name')->get()->keyBy('sales_name');
        $targets = SalesTarget::where('year', $start->year)->where('month', $start->month)->get()->keyBy('sales_id');

        foreach($listSales as $s) {
            $names[] = $s->sales_name;
            $t = $targets->get($s->id); $r = $realSales->get($s->sales_name); $a = $realAR->get($s->sales_name);
            
            $targetIMS[] = $t ? (float)$t->target_ims : 0;
            $targetOA[]  = $t ? (int)$t->target_oa : 0;
            $realIMS[] = $r ? (float)$r->total : 0;
            $realOA[]  = $r ? (int)$r->oa : 0;
            $arLancar[] = $a ? (float)$a->lancar : 0;
            $arMacet[]  = $a ? (float)$a->macet : 0;
        }

        // --- LOGIC SUPPLIER STACKED ---
        $topSuppliers = Penjualan::selectRaw('supplier, SUM(CAST(total_grand AS DECIMAL(20,2))) as total')
            ->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate])
            ->groupBy('supplier')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('supplier')
            ->toArray();

        $salesBySupp = Penjualan::selectRaw("sales_name, supplier, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate])
            ->whereIn('sales_name', $salesNamesList)
            ->groupBy('sales_name', 'supplier')
            ->get();

        $suppSeries = [];
        foreach ($topSuppliers as $suppName) {
            $data = [];
            foreach ($names as $salesName) {
                $row = $salesBySupp->where('sales_name', $salesName)->where('supplier', $suppName)->first();
                $data[] = $row ? (float)$row->total : 0;
            }
            $suppSeries[] = ['name' => $suppName, 'data' => $data];
        }

        $othersData = [];
        foreach ($names as $salesName) {
            $totalSales = $realSales->get($salesName)->total ?? 0;
            $top5Sum = 0;
            foreach ($suppSeries as $series) {
                $idx = array_search($salesName, $names);
                $top5Sum += $series['data'][$idx];
            }
            $othersData[] = max(0, $totalSales - $top5Sum);
        }
        $suppSeries[] = ['name' => 'Others', 'data' => $othersData];

        return [
            'names' => $names,
            'target_ims' => $targetIMS, 'real_ims' => $realIMS,
            'target_oa' => $targetOA, 'real_oa' => $realOA,
            'ar_lancar' => $arLancar, 'ar_macet' => $arMacet,
            'supp_series' => $suppSeries
        ];
    }

    private function baseFilter($query, $dateCol) {
        return $query->whereDate($dateCol, '>=', $this->startDate)->whereDate($dateCol, '<=', $this->endDate)
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