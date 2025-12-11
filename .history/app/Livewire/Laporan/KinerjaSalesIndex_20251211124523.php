<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination; // <--- 1. IMPORT WAJIB
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class KinerjaSalesIndex extends Component
{
    use WithPagination; // <--- 2. GUNAKAN TRAIT INI

    // Filter
    public $bulan;
    public $filterCabang = '';
    public $filterDivisi = ''; 
    
    public $search = ''; // Tambahkan search bar jika diperlukan
    
    // Tab Aktif
    public $activeTab = 'penjualan'; 

    public function mount()
    {
        $this->bulan = date('Y-m');
    }

    public function setTab($tab) { $this->activeTab = $tab; }

    // Reset Page saat filter berubah
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterDivisi() { $this->resetPage(); } 
    public function updatedBulan() { $this->resetPage(); }

    public function render()
    {
        // 1. Setup Tanggal
        $bulanPilih = $this->bulan ?: date('Y-m');
        $dateObj = Carbon::parse($bulanPilih . '-01');
        $start   = $dateObj->startOfMonth()->format('Y-m-d');
        $end     = $dateObj->endOfMonth()->format('Y-m-d');
        
        $selectedYear  = $dateObj->year;
        $selectedMonth = $dateObj->month;

        // 2. Query Master Sales
        $salesQuery = Sales::query();
        
        if ($this->filterCabang) {
            $salesQuery->where('city', $this->filterCabang);
        }

        if ($this->filterDivisi) {
            $salesQuery->where('divisi', $this->filterDivisi);
        }

        // Ambil Sales Aktif
        $salesQuery->where(function($q) {
            $q->where('status', 'Active')
              ->orWhere('status', 'aktif');
        });

        $allSales = $salesQuery->orderBy('sales_name')->get();

        // 3. TARIK DATA TRANSAKSI
        
        // A. Target
        $targets = SalesTarget::where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->get()
            ->keyBy('sales_id');

        // B. Realisasi Penjualan
        $salesStats = Penjualan::selectRaw("
                sales_name, 
                SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_ims,
                COUNT(DISTINCT kode_pelanggan) as total_oa,
                COUNT(DISTINCT trans_no) as total_ec
            ")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name');

        // C. Realisasi AR
        $arStats = AccountReceivable::selectRaw("
                sales_name,
                SUM(CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_ar,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) <= 30 THEN CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2)) ELSE 0 END) as ar_lancar,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) > 30 THEN CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2)) ELSE 0 END) as ar_macet
            ")
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name');

        // D. Pivot Supplier (Top 10)
        // Ambil semua supplier untuk opsi, tapi limit query untuk performa
        $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as val"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereNotNull('supplier')
            ->groupBy('supplier')
            ->orderByDesc('val')
            ->pluck('supplier'); // Ambil Semua tanpa limit agar tabel panjang ke samping

        $rawPivot = Penjualan::selectRaw("
                sales_name, 
                supplier, 
                SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total
            ")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereIn('supplier', $topSuppliers)
            ->groupBy('sales_name', 'supplier')
            ->get();

        $matrixSupplier = [];
        foreach ($rawPivot as $p) {
            $matrixSupplier[$p->sales_name][$p->supplier] = $p->total;
        }

        // 4. MAPPING DATA
        $laporan = [];

        foreach ($allSales as $sales) {
            $name = $sales->sales_name;

            // Target
            $t = $targets->get($sales->id);
            $targetIMS = $t ? (float)$t->target_ims : 0;
            $targetOA  = $t ? (int)$t->target_oa : 0;

            // Penjualan (Cari by Name)
            $stat = $salesStats->get($name) ?? $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));

            $realIMS = $stat ? (float)$stat->total_ims : 0;
            $realOA  = $stat ? (int)$stat->total_oa : 0;
            $ec      = $stat ? (int)$stat->total_ec : 0;

            // AR
            $ar = $arStats->get($name) ?? $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            $arTotal  = $ar ? (float)$ar->total_ar : 0;
            $arLancar = $ar ? (float)$ar->ar_lancar : 0;
            $arMacet  = $ar ? (float)$ar->ar_macet : 0;
            $arPersen = $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0;

            $laporan[] = [
                'nama'         => $name,
                'cabang'       => $sales->city,
                
                // Tab Penjualan
                'target_ims'   => $targetIMS,
                'real_ims'     => $realIMS,
                'persen_ims'   => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                
                // Tab AR
                'ar_total'     => $arTotal,
                'ar_lancar'    => $arLancar,
                'ar_macet'     => $arMacet,
                'persen_macet' => $arPersen,

                // Tab Produktifitas
                'target_oa'    => $targetOA,
                'real_oa'      => $realOA,
                'persen_oa'    => $targetOA > 0 ? ($realOA / $targetOA) * 100 : 0,
                'ec'           => $ec,
            ];
        }

        // Sorting
        $laporan = collect($laporan)->sortByDesc('persen_ims')->values();

        // 5. MANUAL PAGINATION (Agar sesuai Livewire)
        $perPage = 20;
        // Ambil halaman saat ini dari Livewire Paginator
        $currentPage = $this->getPage(); 
        
        $currentItems = $laporan->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $paginatedItems = new LengthAwarePaginator(
            $currentItems, 
            count($laporan), 
            $perPage, 
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // 6. Options Filter
        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));
        $optDivisi = Cache::remember('opt_sales_divisi', 3600, fn() => Sales::select('divisi')->distinct()->whereNotNull('divisi')->orderBy('divisi')->pluck('divisi'));

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan' => $paginatedItems, // Kirim sebagai object Paginator
            'optCabang' => $optCabang,
            'optDivisi' => $optDivisi,
            'topSuppliers' => $topSuppliers,
            'matrixSupplier' => $matrixSupplier
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}