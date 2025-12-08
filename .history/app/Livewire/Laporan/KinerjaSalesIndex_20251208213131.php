<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // Jangan lupa import Cache
use Carbon\Carbon;
use Illuminate\Support\Str;

class KinerjaSalesIndex extends Component
{
    // Filter
    public $bulan;
    public $filterCabang = '';
    public $filterDivisi = ''; // <--- 1. Property Baru
    
    public $activeTab = 'penjualan'; 

    public function mount()
    {
        $this->bulan = date('Y-m');
    }

    public function setTab($tab) { $this->activeTab = $tab; }

    // Reset Page saat filter berubah
    public function updatedFilterCabang() { $this->resetPage(); } // Jika pakai pagination trait
    public function updatedFilterDivisi() { $this->resetPage(); } 

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
        
        // Filter Cabang
        if ($this->filterCabang) {
            $salesQuery->where('city', $this->filterCabang);
        }

        // --- 2. LOGIKA FILTER DIVISI (BARU) ---
        if ($this->filterDivisi) {
            $salesQuery->where('divisi', $this->filterDivisi);
        }

        // Filter Status Aktif
        $salesQuery->where(function($q) {
            $q->where('status', 'Active')
              ->orWhere('status', 'aktif');
        });

        $allSales = $salesQuery->orderBy('sales_name')->get();

        // 3. Eager Loading Data Transaksi (Sama seperti sebelumnya) ...
        // ... (Kode bagian Query Stats Penjualan, AR, Target, Pivot Supplier TETAP SAMA) ...
        // ... (Agar hemat tempat, saya tidak copy paste ulang logika query yang panjang tadi) ...
        // ... Pastikan bagian query $salesStats, $arStats, $targets, $matrixSupplier ada disini ...

        // --- (TEMPELKAN KEMBALI KODE QUERY LAMA ANDA DISINI) ---
        
        // CONTOH SAJA (Gunakan kode lengkap controller sebelumnya untuk bagian ini):
        $targets = SalesTarget::where('year', $selectedYear)->where('month', $selectedMonth)->get()->keyBy('sales_id');
        
        $salesStats = Penjualan::selectRaw("sales_name, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_ims, COUNT(DISTINCT kode_pelanggan) as total_oa, COUNT(DISTINCT trans_no) as total_ec")
            ->whereBetween('tgl_penjualan', [$start, $end])->groupBy('sales_name')->get()->keyBy('sales_name');

        $arStats = AccountReceivable::selectRaw("sales_name, SUM(CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_ar, SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) <= 30 THEN CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2)) ELSE 0 END) as ar_lancar, SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) > 30 THEN CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2)) ELSE 0 END) as ar_macet")
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        // Pivot Supplier Logic (Sama)
        $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as val"))
            ->whereBetween('tgl_penjualan', [$start, $end])->groupBy('supplier')->orderByDesc('val')->limit(10)->pluck('supplier');
        $rawPivot = Penjualan::selectRaw("sales_name, supplier, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")
            ->whereBetween('tgl_penjualan', [$start, $end])->whereIn('supplier', $topSuppliers)->groupBy('sales_name', 'supplier')->get();
        $matrixSupplier = [];
        foreach ($rawPivot as $p) { $matrixSupplier[$p->sales_name][$p->supplier] = $p->total; }


        // 4. MAPPING DATA (Sama seperti sebelumnya) ...
        $laporan = [];
        foreach ($allSales as $sales) {
            // ... (Logika Mapping $laporan[] SAMA PERSIS dengan kode sebelumnya) ...
            // ... Copy dari kode sebelumnya ...
            
            // Saya tulis ulang intinya saja:
            $name = $sales->sales_name;
            $t = $targets->get($sales->id); 
            $stat = $salesStats->get($name) ?? $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            $ar = $arStats->get($name) ?? $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));

            // ... Hitung $targetIMS, $realIMS, dll ...
            $targetIMS = $t ? (float)$t->target_ims : 0;
            $realIMS = $stat ? (float)$stat->total_ims : 0;
            $targetOA  = $t ? (int)$t->target_oa : 0;
            $realOA  = $stat ? (int)$stat->total_oa : 0;
            $ec = $stat ? (int)$stat->total_ec : 0;
            $arTotal = $ar ? (float)$ar->total_ar : 0;
            $arLancar = $ar ? (float)$ar->ar_lancar : 0;
            $arMacet = $ar ? (float)$ar->ar_macet : 0;

            $laporan[] = [
                'nama' => $name,
                'cabang' => $sales->city,
                'target_ims' => $targetIMS,
                'real_ims' => $realIMS,
                'persen_ims' => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                'ar_total' => $arTotal,
                'ar_lancar' => $arLancar,
                'ar_macet' => $arMacet,
                'persen_macet' => $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0,
                'target_oa' => $targetOA,
                'real_oa' => $realOA,
                'persen_oa' => $targetOA > 0 ? ($realOA / $targetOA) * 100 : 0,
                'ec' => $ec,
            ];
        }

        // Sorting
        $laporan = collect($laporan)->sortByDesc('persen_ims')->values();

        // 5. DATA OPSI FILTER (Cached)
        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));
        
        // --- 3. AMBIL OPSI DIVISI (BARU) ---
        $optDivisi = Cache::remember('opt_sales_divisi', 3600, fn() => 
            Sales::select('divisi')->distinct()->whereNotNull('divisi')->orderBy('divisi')->pluck('divisi')
        );

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan' => $laporan,
            'optCabang' => $optCabang,
            'optDivisi' => $optDivisi, // <--- Kirim ke View
            'topSuppliers' => $topSuppliers,
            'matrixSupplier' => $matrixSupplier
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}