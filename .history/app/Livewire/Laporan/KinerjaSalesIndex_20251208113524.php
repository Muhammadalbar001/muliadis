<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget; // Pastikan Model ini di-import
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KinerjaSalesIndex extends Component
{
    public $bulan;
    public $filterCabang = '';

    public function mount()
    {
        $this->bulan = date('Y-m'); // Default Bulan Ini (YYYY-MM)
    }

    public function render()
    {
        // 1. Parse Tanggal & Filter
        $dateObj = Carbon::parse($this->bulan . '-01');
        $start   = $dateObj->startOfMonth()->format('Y-m-d');
        $end     = $dateObj->endOfMonth()->format('Y-m-d');
        
        $selectedYear  = $dateObj->year;
        $selectedMonth = $dateObj->month;

        // 2. Ambil Master Sales
        $salesQuery = Sales::query();
        if ($this->filterCabang) {
            $salesQuery->where('city', $this->filterCabang);
        }
        $allSales = $salesQuery->orderBy('sales_name')->get();

        // 3. EAGER LOADING DATA (Agar Cepat)
        
        // A. Ambil TARGET BULANAN (Sesuai Bulan yang dipilih)
        $targets = SalesTarget::where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->get()
            ->keyBy('sales_id'); // Kunci array pakai Sales ID biar gampang dicocokkan

        // B. Realisasi Penjualan (IMS, OA, EC)
        $salesStats = Penjualan::selectRaw("
                sales_name, 
                SUM(CAST(total_grand AS DECIMAL(20,2))) as total_ims,
                COUNT(DISTINCT kode_pelanggan) as total_oa,
                COUNT(DISTINCT trans_no) as total_ec
            ")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name');

        // C. Realisasi AR (Piutang)
        $arStats = AccountReceivable::selectRaw("
                sales_name,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) <= 30 THEN CAST(nilai AS DECIMAL(20,2)) ELSE 0 END) as ar_lancar,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) > 30 THEN CAST(nilai AS DECIMAL(20,2)) ELSE 0 END) as ar_macet
            ")
            ->where('nilai', '>', 0)
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name');
        
        // 4. MAPPING DATA (Looping)
        $laporan = [];

        foreach ($allSales as $sales) {
            $name = $sales->sales_name;

            // --- AMBIL TARGET DARI TABEL BARU ---
            // Cek apakah ada target di bulan ini? Jika tidak ada, pakai 0.
            $monthlyTarget = $targets->get($sales->id);
            
            $targetIMS = $monthlyTarget ? (float)$monthlyTarget->target_ims : 0;
            $targetOA  = $monthlyTarget ? (int)$monthlyTarget->target_oa : 0;

            // --- AMBIL REALISASI ---
            $statJual = $salesStats->get($name);
            $statAr   = $arStats->get($name);

            // Hitung Penjualan
            $realIMS   = $statJual ? (float) $statJual->total_ims : 0;
            $persenIMS = $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0;
            
            // Hitung AR
            $arLancar  = $statAr ? (float) $statAr->ar_lancar : 0;
            $arMacet   = $statAr ? (float) $statAr->ar_macet : 0;
            $totalAR   = $arLancar + $arMacet;
            $persenMacet = $totalAR > 0 ? ($arMacet / $totalAR) * 100 : 0;

            // Hitung Produktifitas
            $realOA    = $statJual ? (int) $statJual->total_oa : 0;
            $ec        = $statJual ? (int) $statJual->total_ec : 0;
            $persenOA  = $targetOA > 0 ? ($realOA / $targetOA) * 100 : 0;

            $laporan[] = [
                'nama'         => $name,
                'cabang'       => $sales->city,
                
                // Data Penjualan
                'target_ims'   => $targetIMS, // Sekarang ambil dari SalesTarget
                'real_ims'     => $realIMS,
                'persen_ims'   => $persenIMS,
                
                // Data AR
                'ar_lancar'    => $arLancar,
                'ar_macet'     => $arMacet,
                'total_ar'     => $totalAR,
                'persen_macet' => $persenMacet,
                
                // Data OA
                'target_oa'    => $targetOA, // Sekarang ambil dari SalesTarget
                'real_oa'      => $realOA,
                'ec'           => $ec,
                'persen_oa'    => $persenOA,
            ];
        }

        $optCabang = Sales::select('city')->distinct()->whereNotNull('city')->pluck('city');

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan'   => $laporan,
            'optCabang' => $optCabang
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}