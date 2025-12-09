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
    // Filter
    public $filterBulan;
    public $filterTahun;
    public $filterCabang = '';
    
    // Statistik Cards
    public $totalOmzet = 0;
    public $totalOa = 0;
    public $totalEc = 0;

    public function mount()
    {
        $this->filterBulan = date('m');
        $this->filterTahun = date('Y');
    }

    public function updatedFilterBulan() { $this->dispatch('update-charts', data: $this->getChartData()); }
    public function updatedFilterTahun() { $this->dispatch('update-charts', data: $this->getChartData()); }
    public function updatedFilterCabang() { $this->dispatch('update-charts', data: $this->getChartData()); }

    public function render()
    {
        // 1. KPI CARDS (Hitung Ringkasan)
        $dates = $this->getDatesRange();
        $baseQuery = $this->queryPenjualan();

        // Hitung Total Sales (Masuk ke property public $totalOmzet)
        $stats = (clone $baseQuery)->selectRaw("
            SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as omzet,
            COUNT(DISTINCT kode_pelanggan) as oa,
            COUNT(DISTINCT trans_no) as ec
        ")->first();

        $this->totalOmzet = $stats->omzet ?? 0;
        $this->totalOa    = $stats->oa ?? 0;
        $this->totalEc    = $stats->ec ?? 0;

        // Hitung KPI Lainnya (Local Variable)
        $returSum = $this->queryRetur()->sum(DB::raw("CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        $arSum    = $this->queryAR()->sum(DB::raw("CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        $collSum  = $this->queryCollection()->sum(DB::raw("CAST(REPLACE(REPLACE(receive_amount, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        
        $persenRetur = $this->totalOmzet > 0 ? ($returSum / $this->totalOmzet) * 100 : 0;

        // 2. FILTER OPTIONS
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        // 3. CHART DATA
        $chartData = $this->getChartData();

        // PERBAIKAN: Hapus 'salesSum' dari compact karena sudah pakai $totalOmzet (public)
        return view('livewire.dashboard-index', compact(
            'returSum', 'arSum', 'collSum', 'persenRetur', // salesSum dihapus
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

        // B. TOP 10 RANKING (LOGIKA BARU - Diambil sebagai Collection)
        
        // 1. Top Produk (Qty)
        $topProduk = $this->queryPenjualan()
            ->selectRaw("nama_item, SUM(CAST(REPLACE(qty, ',', '') AS DECIMAL(20,2))) as total_qty")
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

        // C. SALESMAN PERFORMANCE (Hanya Ringkas untuk Grafik)
        $salesPerf = $this->getSalesmanData();

        return [
            'dates' => $dates,
            'sales' => $dataSales, 'retur' => $dataRetur, 'ar' => $dataAR, 'coll' => $dataColl,
            
            // Top Ranking Data (Dikirim ke JS)
            'topProdNames' => $topProduk->pluck('name'), 'topProdVal' => $topProduk->pluck('value'),
            'topCustNames' => $topCustomer->pluck('name'), 'topCustVal' => $topCustomer->pluck('value'),
            'topSuppNames' => $topSupplier->pluck('name'), 'topSuppVal' => $topSupplier->pluck('value'),

            // Salesman Data
            'salesNames'     => $salesPerf['names'],
            'salesRealIMS'   => $salesPerf['real_ims'],
            'salesTargetIMS' => $salesPerf['target_ims'],
        ];
    }

    private function getSalesmanData() {
        $q = Penjualan::selectRaw("sales_name, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")
            ->whereBetween('tgl_penjualan', [
                Carbon::createFromDate($this->filterTahun, $this->filterBulan, 1)->startOfMonth(), 
                Carbon::createFromDate($this->filterTahun, $this->filterBulan, 1)->endOfMonth()
            ])
            ->groupBy('sales_name')
            ->orderByDesc('total')
            ->limit(10) // Top 10 Sales
            ->get();
        
        return [
            'names' => $q->pluck('sales_name'),
            'real_ims' => $q->pluck('total')->map(fn($v) => (float)$v),
            'target_ims' => $q->pluck('total')->map(fn($v) => (float)$v * 1.1) // Dummy Target +10%
        ];
    }

    // Helper Filter Tanggal & Cabang
    private function baseFilter($query, $dateCol) {
        $dateObj = Carbon::createFromDate($this->filterTahun, $this->filterBulan, 1);
        return $query->whereBetween($dateCol, [$dateObj->startOfMonth(), $dateObj->endOfMonth()])
            ->when(!empty($this->filterCabang), fn($q) => $q->where('cabang', $this->filterCabang));
    }
    
    private function queryPenjualan() { return $this->baseFilter(Penjualan::query(), 'tgl_penjualan'); }
    private function queryRetur()     { return $this->baseFilter(Retur::query(), 'tgl_retur'); }
    private function queryAR()        { return $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan'); }
    private function queryCollection(){ 
        $dateObj = Carbon::createFromDate($this->filterTahun, $this->filterBulan, 1);
        return Collection::query()->whereBetween('tanggal', [$dateObj->startOfMonth(), $dateObj->endOfMonth()])
            ->when(!empty($this->filterCabang), fn($q) => $q->where('cabang', $this->filterCabang));
    }
    
    private function getDatesRange() {
        $dateObj = Carbon::createFromDate($this->filterTahun, $this->filterBulan, 1);
        $dates = []; $c = $dateObj->copy()->startOfMonth(); $e = $dateObj->copy()->endOfMonth();
        while ($c <= $e) { $dates[] = $c->format('Y-m-d'); $c->addDay(); }
        return $dates;
    }
}