<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DashboardIndex extends Component
{
    public $startDate;
    public $endDate;
    public $filterCabang = [];
    public $filterSales = [];

    // Properti Public untuk KPI Card (Agar bisa diakses View langsung)
    public $salesSum = 0;
    public $returSum = 0;
    public $arSum = 0;
    public $collSum = 0;
    public $persenRetur = 0;

    public function mount()
    {
        // Default awal bulan ini s/d hari ini
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    // Listener saat properti berubah (live update)
    public function updated($propertyName) 
    { 
        $this->dispatch('update-charts', data: $this->getChartData());
    }

    public function render()
    {
        // 1. KPI CARDS (Hitung Total dengan Pembersihan Format Angka)
        // Rumus: Hapus titik, ganti koma dengan titik (format Indo: 1.000.000,00 -> 1000000.00)
        $this->salesSum = $this->queryPenjualan()->sum(DB::raw("CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        $this->returSum = $this->queryRetur()->sum(DB::raw("CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        $this->arSum    = $this->queryAR()->sum(DB::raw("CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        $this->collSum  = $this->queryCollection()->sum(DB::raw("CAST(REPLACE(REPLACE(receive_amount, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        
        $this->persenRetur = $this->salesSum > 0 ? ($this->returSum / $this->salesSum) * 100 : 0;

        // 2. FILTER OPTIONS (Cached untuk performa)
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        // 3. AMBIL DATA CHART LENGKAP (Termasuk Ranking)
        $chartData = $this->getChartData();

        return view('livewire.dashboard-index', compact(
            'optCabang', 'optSales', 'chartData'
        ))->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    private function getChartData()
    {
        $dates = $this->getDatesRange();

        // A. TREND HARIAN
        $dailySales = $this->queryPenjualan()->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();
        $dailyRetur = $this->queryRetur()->selectRaw("DATE_FORMAT(tgl_retur, '%Y-%m-%d') as tgl, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();
        $dailyAR    = $this->queryAR()->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(REPLACE(REPLACE(total_nilai, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();
        $dailyColl  = $this->queryCollection()->selectRaw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tgl, SUM(CAST(REPLACE(REPLACE(receive_amount, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        $dataSales = []; $dataRetur = []; $dataAR = []; $dataColl = [];
        foreach ($dates as $date) {
            $dataSales[] = (float)($dailySales[$date] ?? 0);
            $dataRetur[] = (float)($dailyRetur[$date] ?? 0);
            $dataAR[]    = (float)($dailyAR[$date] ?? 0);
            $dataColl[]  = (float)($dailyColl[$date] ?? 0);
        }

        // B. SALESMAN PERFORMANCE & SUPPLIER BREAKDOWN
        // (Pastikan method getSalesmanPerformance() Anda sudah ada dan benar, 
        //  jika belum, gunakan logika sederhana atau kosongkan array sementara)
        $salesPerf = [
             'names' => [], 'target_ims' => [], 'real_ims' => [], 
             'target_oa' => [], 'real_oa' => [], 'ar_lancar' => [], 'ar_macet' => [], 'supp_series' => []
        ];
        // $salesPerf = $this->getSalesmanPerformance(); // Uncomment jika method ini ada

        // C. TOP RANKING (LOGIKA PERBAIKAN)
        // Pindahkan query ranking kesini agar ikut ter-update

        // 1. Top Produk (Qty)
        $topProduk = $this->queryPenjualan()
            ->selectRaw("nama_item, SUM(CAST(REPLACE(REPLACE(qty, ',', ''), '.', '') AS DECIMAL(20,2))) as total_qty") // Asumsi qty pakai koma/titik
            ->groupBy('nama_item')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['name' => Str::limit($item->nama_item, 20), 'value' => (int)$item->total_qty]);

        // 2. Top Customer (Value)
        $topCustomer = $this->queryPenjualan()
            ->selectRaw("nama_pelanggan, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_beli")
            ->groupBy('nama_pelanggan')
            ->orderByDesc('total_beli')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['name' => Str::limit($item->nama_pelanggan, 15), 'value' => (float)$item->total_beli]);

        // 3. Top Supplier (Value)
        $topSupplier = $this->queryPenjualan()
            ->selectRaw("supplier, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_beli")
            ->whereNotNull('supplier')->where('supplier', '!=', '')
            ->groupBy('supplier')
            ->orderByDesc('total_beli')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['name' => Str::limit($item->supplier, 15), 'value' => (float)$item->total_beli]);

        return [
            'dates' => $dates,
            'sales' => $dataSales, 'retur' => $dataRetur, 'ar' => $dataAR, 'coll' => $dataColl,
            
            // Salesman Data (Placeholder jika method belum ada)
            'salesNames'     => $salesPerf['names'],
            'salesTargetIMS' => $salesPerf['target_ims'], 'salesRealIMS' => $salesPerf['real_ims'],
            'salesTargetOA'  => $salesPerf['target_oa'],  'salesRealOA'  => $salesPerf['real_oa'],
            'salesARLancar'  => $salesPerf['ar_lancar'],  'salesARMacet' => $salesPerf['ar_macet'],
            'salesSuppSeries'=> $salesPerf['supp_series'],
            
            // DATA RANKING (Dikirim ke JS untuk grafik)
            'topProdNames' => $topProduk->pluck('name'), 'topProdVal' => $topProduk->pluck('value'),
            'topCustNames' => $topCustomer->pluck('name'), 'topCustVal' => $topCustomer->pluck('value'),
            'topSuppNames' => $topSupplier->pluck('name'), 'topSuppVal' => $topSupplier->pluck('value'),
        ];
    }

    // Helper Filter
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