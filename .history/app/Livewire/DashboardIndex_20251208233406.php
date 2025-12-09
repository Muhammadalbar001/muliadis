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

        $topSupplier = $this->queryPenjualan()
            ->selectRaw("supplier, SUM(CAST(total_grand AS DECIMAL(20,2))) as total_beli")
            ->groupBy('supplier')
            ->orderByDesc('total_beli')
            ->limit(10)
            ->get();

        // 3. FILTER
        $optCabang = Cache::remember('dash_cabang', 3600, fn() =>
            Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang')
        );

        $optSales  = Cache::remember('dash_sales', 3600, fn() =>
            Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name')
        );

        // --- DATA UNTUK GRAFIK RANKING ---
        $topProductLabels   = $topProduk->pluck('nama_item')->toArray();
        $topProductData     = $topProduk->pluck('total_qty')->toArray();

        $topCustomerLabels  = $topCustomer->pluck('nama_pelanggan')->toArray();
        $topCustomerData    = $topCustomer->pluck('total_beli')->toArray();

        $topSupplierLabels  = $topSupplier->pluck('supplier')->toArray();
        $topSupplierData    = $topSupplier->pluck('total_beli')->toArray();

        $chartData = $this->getChartData();

        return view('livewire.dashboard-index', compact(
            'salesSum', 'returSum', 'arSum', 'collSum', 'persenRetur',
            'topProduk', 'topCustomer', 'topSupplier',
            'topProductLabels', 'topProductData',
            'topCustomerLabels', 'topCustomerData',
            'topSupplierLabels', 'topSupplierData',
            'optCabang', 'optSales', 'chartData'
        ))->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    private function getChartData()
    {
        $dates = $this->getDatesRange();

        // Trend Harian
        $dailySales = $this->queryPenjualan()
            ->selectRaw("DATE_FORMAT(tgl_penjualan,'%Y-%m-%d') as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')
            ->pluck('total', 'tgl')
            ->toArray();

        $dailyRetur = $this->queryRetur()
            ->selectRaw("DATE_FORMAT(tgl_retur,'%Y-%m-%d') as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')
            ->pluck('total', 'tgl')
            ->toArray();

        $dailyAR = $this->queryAR()
            ->selectRaw("DATE_FORMAT(tgl_penjualan,'%Y-%m-%d') as tgl, SUM(CAST(total_nilai AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')
            ->pluck('total', 'tgl')
            ->toArray();

        $dailyColl = $this->queryCollection()
            ->selectRaw("DATE_FORMAT(tanggal,'%Y-%m-%d') as tgl, SUM(CAST(receive_amount AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')
            ->pluck('total', 'tgl')
            ->toArray();

        return [
            'dates' => $dates,
            'sales' => $dailySales,
            'retur' => $dailyRetur,
            'ar'    => $dailyAR,
            'coll'  => $dailyColl,
        ];
    }

    private function baseFilter($query, $dateCol)
    {
        return $query
            ->whereDate($dateCol, '>=', $this->startDate)
            ->whereDate($dateCol, '<=', $this->endDate)
            ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
            ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales));
    }

    private function queryPenjualan()
    {
        return $this->baseFilter(Penjualan::query(), 'tgl_penjualan');
    }
    private function queryRetur()
    {
        return $this->baseFilter(Retur::query(), 'tgl_retur');
    }
    private function queryAR()
    {
        return $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan');
    }
    private function queryCollection()
    {
        return Collection::query()
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
            ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales));
    }

    private function getDatesRange()
    {
        $dates = [];
        $c = Carbon::parse($this->startDate);
        $e = Carbon::parse($this->endDate);

        while ($c <= $e) {
            $dates[] = $c->format('Y-m-d');
            $c->addDay();
        }
        return $dates;
    }
}
