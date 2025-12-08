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

class DashboardIndex extends Component
{
    // Filter
    public $startDate;
    public $endDate;
    public $filterCabang = [];
    public $filterSales = [];

    public function mount()
    {
        // Default awal bulan ini s/d hari ini
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    // Listener: Saat filter berubah, kirim data baru ke JS
    public function updated($propertyName) 
    { 
        $this->dispatch('update-charts', data: $this->getChartData());
    }

    public function render()
    {
        // 1. QUERY SUMMARY (KPI CARDS)
        $salesSum = $this->queryPenjualan()->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $returSum = $this->queryRetur()->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $arSum    = $this->queryAR()->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));
        $collSum  = $this->queryCollection()->sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));
        
        $persenRetur = $salesSum > 0 ? ($returSum / $salesSum) * 100 : 0;

        // 2. TOP 10 (Ranking)
        $topProduk = $this->queryPenjualan()
            ->selectRaw("nama_item, SUM(CAST(qty AS DECIMAL(20,2))) as total_qty")
            ->groupBy('nama_item')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $topCustomer = $this->queryPenjualan()
            ->selectRaw("nama_pelanggan, SUM(CAST(total_grand AS DECIMAL(20,2))) as total_beli")
            ->groupBy('nama_pelanggan')
            ->orderByDesc('total_beli')
            ->limit(10)
            ->get();

        // 3. OPSI FILTER
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        // Data Grafik Awal
        $chartData = $this->getChartData();

        return view('livewire.dashboard-index', compact(
            'salesSum', 'returSum', 'arSum', 'collSum', 'persenRetur',
            'topProduk', 'topCustomer',
            'optCabang', 'optSales', 'chartData'
        ))->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    // --- LOGIC DATA GRAFIK ---
    private function getChartData()
    {
        $dates = $this->getDatesRange();

        // Ambil Data Harian
        $dailySales = $this->queryPenjualan()
            ->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        $dailyRetur = $this->queryRetur()
            ->selectRaw("DATE_FORMAT(tgl_retur, '%Y-%m-%d') as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        $dailyAR = $this->queryAR()
            ->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(total_nilai AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        $dailyColl = $this->queryCollection()
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tgl, SUM(CAST(receive_amount AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        // Mapping Data (Pastikan FLOAT agar grafik muncul)
        $dataSales = []; $dataRetur = [];
        $dataAR = [];    $dataColl = [];

        foreach ($dates as $date) {
            $dataSales[] = (float) ($dailySales[$date] ?? 0);
            $dataRetur[] = (float) ($dailyRetur[$date] ?? 0);
            $dataAR[]    = (float) ($dailyAR[$date] ?? 0);
            $dataColl[]  = (float) ($dailyColl[$date] ?? 0);
        }

        return [
            'dates' => $dates,
            'sales' => $dataSales,
            'retur' => $dataRetur,
            'ar'    => $dataAR,
            'coll'  => $dataColl
        ];
    }

    // --- HELPER FILTER ---
    private function baseFilter($query, $dateCol) {
        // Gunakan whereDate agar presisi (YYYY-MM-DD)
        return $query->whereDate($dateCol, '>=', $this->startDate)
                     ->whereDate($dateCol, '<=', $this->endDate)
                     ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
                     ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales));
    }

    private function queryPenjualan() { return $this->baseFilter(Penjualan::query(), 'tgl_penjualan'); }
    private function queryRetur()     { return $this->baseFilter(Retur::query(), 'tgl_retur'); }
    private function queryAR()        { return $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan'); }
    private function queryCollection(){ 
        $q = Collection::query()
                ->whereDate('tanggal', '>=', $this->startDate)
                ->whereDate('tanggal', '<=', $this->endDate)
                ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang));
        
        if(!empty($this->filterSales)) {
            $q->whereIn('sales_name', $this->filterSales);
        }
        return $q;
    }

    private function getDatesRange() {
        $dates = [];
        $current = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        while ($current <= $end) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }
        return $dates;
    }
}