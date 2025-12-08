<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class KinerjaSalesIndex extends Component
{
    // Filter
    public $bulan;
    public $filterCabang = '';
    
    // Tab Aktif (Agar tabel terpisah dan rapi)
    public $activeTab = 'penjualan'; // Options: penjualan, ar, supplier, produktifitas

    public function mount()
    {
        $this->bulan = date('Y-m'); // Default bulan ini
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        // 1. Setup Tanggal
        $dateObj = Carbon::parse($this->bulan . '-01');
        $start   = $dateObj->startOfMonth()->format('Y-m-d');
        $end     = $dateObj->endOfMonth()->format('Y-m-d');
        $selectedYear  = $dateObj->year;
        $selectedMonth = $dateObj->month;

        // 2. Ambil Master Sales (PERBAIKAN STATUS)
        $salesQuery = Sales::query();
        
        // Filter Cabang (Gunakan kolom 'city')
        if ($this->filterCabang) {
            $salesQuery->where('city', $this->filterCabang);
        }

        // Ambil Sales yang Statusnya Active/aktif
        $salesQuery->where(function($q) {
            $q->where('status', 'Active')
              ->orWhere('status', 'aktif')
              ->orWhere('status', 'Active');
        });

        $allSales = $salesQuery->orderBy('sales_name')->get();

        // 3. TARIK DATA TRANSAKSI (Eager Loading Manual)
        
        // A. Target Bulanan
        $targets = SalesTarget::where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->get()
            ->keyBy('sales_id');

        // B. Realisasi Penjualan (Gunakan REPLACE agar format 1.000.000 terbaca angka)
        // Kita asumsikan format data di DB bersih, jika tidak kita pakai cleaning sederhana
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

        // C. Realisasi AR (Snapshot saat ini)
        $arStats = AccountReceivable::selectRaw("
                sales_name,
                SUM(CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_ar,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) <= 30 THEN CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2)) ELSE 0 END) as ar_lancar,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) > 30 THEN CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2)) ELSE 0 END) as ar_macet
            ")
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name');

        // D. Data Pivot Supplier (Top 10)
        $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as val"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('supplier')
            ->orderByDesc('val')
            // ->limit(10)
            ->pluck('supplier');

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

        // 4. MAPPING DATA (Gabungkan ke Master Sales)
        $laporan = [];

        foreach ($allSales as $sales) {
            $name = $sales->sales_name;

            // Target
            $t = $targets->get($sales->id);
            $targetIMS = $t ? (float)$t->target_ims : 0;
            $targetOA  = $t ? (int)$t->target_oa : 0;

            // Penjualan
            $stat = $salesStats->get($name);
            // Coba cari nama persis, atau coba uppercase jika tidak ketemu
            if (!$stat) {
                // Fallback search (Opsional, jika nama di master beda case dengan transaksi)
                $stat = $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            }

            $realIMS = $stat ? (float)$stat->total_ims : 0;
            $realOA  = $stat ? (int)$stat->total_oa : 0;
            $ec      = $stat ? (int)$stat->total_ec : 0;

            // AR
            $ar = $arStats->get($name);
            if (!$ar) {
                $ar = $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            }
            $arTotal  = $ar ? (float)$ar->total_ar : 0;
            $arLancar = $ar ? (float)$ar->ar_lancar : 0;
            $arMacet  = $ar ? (float)$ar->ar_macet : 0;
            $arPersen = $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0;

            $laporan[] = [
                'nama'         => $name,
                'cabang'       => $sales->city, // Pakai kolom city
                
                // Tab 1: Penjualan
                'target_ims'   => $targetIMS,
                'real_ims'     => $realIMS,
                'persen_ims'   => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                
                // Tab 2: AR
                'ar_total'     => $arTotal,
                'ar_lancar'    => $arLancar,
                'ar_macet'     => $arMacet,
                'persen_macet' => $arPersen,

                // Tab 3: Produktifitas
                'target_oa'    => $targetOA,
                'real_oa'      => $realOA,
                'persen_oa'    => $targetOA > 0 ? ($realOA / $targetOA) * 100 : 0,
                'ec'           => $ec,
            ];
        }

        // Sorting Default (Berdasarkan Omzet)
        $laporan = collect($laporan)->sortByDesc('persen_ims')->values();

        // Opsi Cabang
        $optCabang = Sales::select('city')->distinct()->whereNotNull('city')->pluck('city');

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan'        => $laporan,
            'optCabang'      => $optCabang,
            'topSuppliers'   => $topSuppliers,
            'matrixSupplier' => $matrixSupplier
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}