<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Master\Sales;
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KinerjaSalesIndex extends Component
{
    // Filter
    public $bulan; // Format: YYYY-MM (2025-10)
    public $filterCabang = '';

    public function mount()
    {
        // Default bulan ini
        $this->bulan = date('Y-m');
    }

    public function render()
    {
        // 1. Ambil Semua Sales (Master Data)
        // Kita gunakan LEFT JOIN nanti atau eager loading logic manual
        $salesQuery = Sales::query();
        
        if ($this->filterCabang) {
            $salesQuery->where('city', $this->filterCabang);
        }

        $allSales = $salesQuery->orderBy('sales_name')->get();

        // 2. Siapkan Wadah Data (Array)
        $laporan = [];

        // Parse Bulan
        $date = Carbon::parse($this->bulan . '-01');
        $startOfMonth = $date->startOfMonth()->format('Y-m-d');
        $endOfMonth   = $date->endOfMonth()->format('Y-m-d');

        foreach ($allSales as $sales) {
            
            // --- A. DATA PENJUALAN (IMS) ---
            // Target
            $targetIMS = (float) $sales->target_ims;
            
            // Realisasi (Total Grand Penjualan Bulan Ini)
            $realisasiIMS = Penjualan::where('sales_name', $sales->sales_name)
                ->whereBetween('tgl_penjualan', [$startOfMonth, $endOfMonth])
                ->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
            
            // Persentase
            $persenIMS = $targetIMS > 0 ? ($realisasiIMS / $targetIMS) * 100 : 0;


            // --- B. DATA AR (PIUTANG) ---
            // AR Reguler (Lancar): Umur <= 30 Hari
            $arLancar = AccountReceivable::where('sales_name', $sales->sales_name)
                ->where('nilai', '>', 0) // Masih ada sisa hutang
                ->whereRaw("CAST(umur_piutang AS UNSIGNED) <= 30")
                ->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));

            // AR Macet (Overdue): Umur > 30 Hari
            $arMacet = AccountReceivable::where('sales_name', $sales->sales_name)
                ->where('nilai', '>', 0)
                ->whereRaw("CAST(umur_piutang AS UNSIGNED) > 30")
                ->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));

            $totalAR = $arLancar + $arMacet;
            // % Bad Debt (Berapa persen yang macet dari total piutang)
            $persenMacet = $totalAR > 0 ? ($arMacet / $totalAR) * 100 : 0;


            // --- C. PRODUKTIFITAS (OA & EC) ---
            // Target OA (Dari Master)
            $targetOA = (float) $sales->target_oa;

            // Realisasi OA (Outlet Aktif): Jumlah Toko Unik yang beli bulan ini
            $realisasiOA = Penjualan::where('sales_name', $sales->sales_name)
                ->whereBetween('tgl_penjualan', [$startOfMonth, $endOfMonth])
                ->distinct('kode_pelanggan') // Hitung customer unik
                ->count('kode_pelanggan');

            // EC (Effective Call): Jumlah Nota/Transaksi bulan ini
            // (Satu toko bisa beli berkali-kali, jadi EC >= OA)
            $ec = Penjualan::where('sales_name', $sales->sales_name)
                ->whereBetween('tgl_penjualan', [$startOfMonth, $endOfMonth])
                ->count(); // Hitung baris/nota (asumsi 1 baris = 1 transaksi item, atau group by no_trans)
            
            // Koreksi EC: Sebaiknya hitung Distinct Trans No
            $ec_fix = Penjualan::where('sales_name', $sales->sales_name)
                ->whereBetween('tgl_penjualan', [$startOfMonth, $endOfMonth])
                ->distinct('trans_no')
                ->count('trans_no');

            $persenOA = $targetOA > 0 ? ($realisasiOA / $targetOA) * 100 : 0;

            // Masukkan ke Array
            $laporan[] = [
                'nama' => $sales->sales_name,
                'cabang' => $sales->city,
                // Sales
                'target_ims' => $targetIMS,
                'real_ims'   => $realisasiIMS,
                'persen_ims' => $persenIMS,
                // AR
                'ar_lancar'  => $arLancar,
                'ar_macet'   => $arMacet, // > 30 Hari
                'total_ar'   => $totalAR,
                'persen_macet' => $persenMacet,
                // Produktifitas
                'target_oa'  => $targetOA,
                'real_oa'    => $realisasiOA,
                'ec'         => $ec_fix,
                'persen_oa'  => $persenOA
            ];
        }

        // Ambil Opsi Cabang
        $optCabang = Sales::select('city')->distinct()->whereNotNull('city')->pluck('city');

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan'   => $laporan,
            'optCabang' => $optCabang
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}