<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use App\Models\Master\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardIndex extends Component
{
    // Filter Properties
    public $startDate;
    public $endDate;
    public $filterCabang = 'all'; // all, atau nama cabang
    public $filterSales = 'all';

    public function mount()
    {
        // Default: Bulan Ini
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    public function render()
    {
        // 1. QUERY DASAR (Base Query)
        // Kita siapkan query dasar yang sudah difilter tanggal & cabang
        // agar tidak menulis ulang where berulang kali.
        
        $salesQuery = Penjualan::query()
            ->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
            
        $returQuery = Retur::query()
            ->whereBetween('tgl_retur', [$this->startDate, $this->endDate]);

        $arQuery = AccountReceivable::query() // AR biasanya akumulasi, tapi kita lihat yg faktur tgl ini
            ->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
            
        $collQuery = Collection::query()
            ->whereBetween('tanggal', [$this->startDate, $this->endDate]);

        // Apply Filter Tambahan
        if ($this->filterCabang != 'all') {
            $salesQuery->where('cabang', $this->filterCabang);
            $returQuery->where('cabang', $this->filterCabang);
            $arQuery->where('cabang', $this->filterCabang);
            $collQuery->where('cabang', $this->filterCabang);
        }

        if ($this->filterSales != 'all') {
            $salesQuery->where('sales_name', $this->filterSales);
            // Retur/AR/Coll juga bisa difilter sales jika ada kolomnya
        }

        // 2. HITUNG KARTU STATISTIK (KPI)
        // Gunakan CAST karena kolom di DB string (akibat import excel)
        $totalPenjualan = $salesQuery->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $totalRetur     = $returQuery->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $totalAR        = $arQuery->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))')); // Sisa Piutang
        $totalCollection= $collQuery->sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));
        
        $persenRetur = $totalPenjualan > 0 ? ($totalRetur / $totalPenjualan) * 100 : 0;

        // 3. SIAPKAN DATA GRAFIK
        
        // A. Trend Penjualan Harian
        $dailySales = Penjualan::query()
            ->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate])
            ->when($this->filterCabang != 'all', fn($q) => $q->where('cabang', $this->filterCabang))
            ->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->pluck('total', 'tgl');

        // B. Top 5 Produk (Berdasarkan Qty atau Nilai)
        $topProduk = Penjualan::query()
            ->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate])
            ->when($this->filterCabang != 'all', fn($q) => $q->where('cabang', $this->filterCabang))
            ->selectRaw("nama_item, SUM(CAST(qty AS DECIMAL(20,2))) as total_qty")
            ->groupBy('nama_item')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // C. Top 5 Customer
        $topCustomer = Penjualan::query()
            ->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate])
            ->when($this->filterCabang != 'all', fn($q) => $q->where('cabang', $this->filterCabang))
            ->selectRaw("nama_pelanggan, SUM(CAST(total_grand AS DECIMAL(20,2))) as total_beli")
            ->groupBy('nama_pelanggan')
            ->orderByDesc('total_beli')
            ->limit(5)
            ->get();
            
        // 4. DATA OPSI FILTER
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->pluck('sales_name'));

        return view('livewire.dashboard-index', compact(
            'totalPenjualan', 'totalRetur', 'totalAR', 'totalCollection', 'persenRetur',
            'dailySales', 'topProduk', 'topCustomer', 'optCabang', 'optSales'
        ))->layout('layouts.app', ['header' => 'Dashboard Executive']);
    }
}