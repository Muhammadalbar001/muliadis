<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardIndex extends Component
{
    public $filterBulan;
    public $filterTahun;
    public $filterCabang = '';
    
    // Data untuk Grafik Ranking
    public $topProducts = [];
    public $topCustomers = [];
    public $topSuppliers = [];

    public function mount()
    {
        $this->filterBulan = date('m');
        $this->filterTahun = date('Y');
    }

    public function updatedFilterBulan() { $this->loadData(); }
    public function updatedFilterTahun() { $this->loadData(); }
    public function updatedFilterCabang() { $this->loadData(); }

    public function render()
    {
        $this->loadData(); // Panggil fungsi load data

        // Statistik Utama (Cards)
        // ... (Kode query statistik lama Anda simpan disini atau gabungkan) ...
        
        // Ambil Opsi Cabang
        $optCabang = Cache::remember('opt_dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->pluck('cabang'));

        return view('livewire.dashboard-index', [
            'optCabang' => $optCabang,
        ])->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    public function loadData()
    {
        // 1. Setup Tanggal Filter
        $dateObj = Carbon::createFromDate($this->filterTahun, $this->filterBulan, 1);
        $start = $dateObj->startOfMonth()->format('Y-m-d');
        $end   = $dateObj->endOfMonth()->format('Y-m-d');

        // Helper Query (Supaya tidak ulang-ulang)
        $baseQuery = Penjualan::whereBetween('tgl_penjualan', [$start, $end]);
        if ($this->filterCabang) {
            $baseQuery->where('cabang', $this->filterCabang);
        }

        // --- 1. TOP 10 PRODUK (Berdasarkan QTY) ---
        $this->topProducts = (clone $baseQuery)
            ->select('nama_item', DB::raw("SUM(CAST(REPLACE(qty, ',', '') AS UNSIGNED)) as total_qty"))
            ->groupBy('nama_item')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['label' => \Illuminate\Support\Str::limit($item->nama_item, 20), 'value' => (int)$item->total_qty]);

        // --- 2. TOP 10 CUSTOMER (Berdasarkan Value/Omzet) ---
        $this->topCustomers = (clone $baseQuery)
            ->select('nama_pelanggan', DB::raw("SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_val"))
            ->groupBy('nama_pelanggan')
            ->orderByDesc('total_val')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['label' => \Illuminate\Support\Str::limit($item->nama_pelanggan, 15), 'value' => (float)$item->total_val]);

        // --- 3. TOP 10 SUPPLIER (Berdasarkan Omzet) ---
        $this->topSuppliers = (clone $baseQuery)
            ->select('supplier', DB::raw("SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_omzet"))
            ->whereNotNull('supplier')
            ->where('supplier', '!=', '')
            ->groupBy('supplier')
            ->orderByDesc('total_omzet')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['label' => \Illuminate\Support\Str::limit($item->supplier, 15), 'value' => (float)$item->total_omzet]);
    }
}