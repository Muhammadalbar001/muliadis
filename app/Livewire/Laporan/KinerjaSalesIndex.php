<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
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
        $this->bulan = date('Y-m');
    }

    public function render()
    {
        // 1. Date Filter
        $dateObj = Carbon::parse($this->bulan . '-01');
        $start   = $dateObj->startOfMonth()->format('Y-m-d');
        $end     = $dateObj->endOfMonth()->format('Y-m-d');
        
        $selectedYear  = $dateObj->year;
        $selectedMonth = $dateObj->month;

        // 2. Master Sales
        $salesQuery = Sales::query();
        if ($this->filterCabang) {
            $salesQuery->where('city', $this->filterCabang);
        }
        $allSales = $salesQuery->orderBy('sales_name')->get();

        // 3. EAGER LOADING DATA UTAMA (IMS, AR, OA)
        
        // A. Target Bulanan
        $targets = SalesTarget::where('year', $selectedYear)->where('month', $selectedMonth)->get()->keyBy('sales_id');

        // B. Realisasi Penjualan (IMS, OA, EC)
        $salesStats = Penjualan::selectRaw("
                sales_name, 
                SUM(CAST(total_grand AS DECIMAL(20,2))) as total_ims,
                COUNT(DISTINCT kode_pelanggan) as total_oa,
                COUNT(DISTINCT trans_no) as total_ec
            ")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name')
            ->get()->keyBy('sales_name');

        // C. Realisasi AR
        $arStats = AccountReceivable::selectRaw("
                sales_name,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) <= 30 THEN CAST(nilai AS DECIMAL(20,2)) ELSE 0 END) as ar_lancar,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) > 30 THEN CAST(nilai AS DECIMAL(20,2)) ELSE 0 END) as ar_macet
            ")
            ->where('nilai', '>', 0)
            ->groupBy('sales_name')
            ->get()->keyBy('sales_name');

        // -------------------------------------------------------------
        // 4. LOGIKA BARU: PIVOT TABLE SUPPLIER (Sales x Supplier)
        // -------------------------------------------------------------
        
        // Ambil data penjualan dikelompokkan berdasarkan Sales & Supplier
        $rawPivot = Penjualan::selectRaw("
                sales_name, 
                supplier, 
                SUM(CAST(total_grand AS DECIMAL(20,2))) as total
            ")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name', 'supplier')
            ->get();

        // Ambil Daftar Supplier Unik (Untuk Judul Kolom Header)
        // Kita urutkan abjad agar rapi
        $listSuppliers = $rawPivot->pluck('supplier')->unique()->sort()->values();

        // Buat Matriks Data: $matrix['NamaSales']['NamaSupplier'] = Nilai
        $matrixSupplier = [];
        foreach ($rawPivot as $row) {
            $matrixSupplier[$row->sales_name][$row->supplier] = $row->total;
        }

        // -------------------------------------------------------------
        // 5. MAPPING LAPORAN UTAMA (Looping Sales)
        // -------------------------------------------------------------
        $laporan = [];

        foreach ($allSales as $sales) {
            $name = $sales->sales_name;

            // Target
            $monthlyTarget = $targets->get($sales->id);
            $targetIMS = $monthlyTarget ? (float)$monthlyTarget->target_ims : 0;
            $targetOA  = $monthlyTarget ? (int)$monthlyTarget->target_oa : 0;

            // Realisasi
            $statJual = $salesStats->get($name);
            $statAr   = $arStats->get($name);

            $realIMS   = $statJual ? (float) $statJual->total_ims : 0;
            $realOA    = $statJual ? (int) $statJual->total_oa : 0;
            $ec        = $statJual ? (int) $statJual->total_ec : 0;
            
            $arLancar  = $statAr ? (float) $statAr->ar_lancar : 0;
            $arMacet   = $statAr ? (float) $statAr->ar_macet : 0;
            $totalAR   = $arLancar + $arMacet;

            $laporan[] = [
                'nama'         => $name,
                'cabang'       => $sales->city,
                'target_ims'   => $targetIMS,
                'real_ims'     => $realIMS,
                'persen_ims'   => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                'ar_lancar'    => $arLancar,
                'ar_macet'     => $arMacet,
                'total_ar'     => $totalAR,
                'persen_macet' => $totalAR > 0 ? ($arMacet / $totalAR) * 100 : 0,
                'target_oa'    => $targetOA,
                'real_oa'      => $realOA,
                'ec'           => $ec,
                'persen_oa'    => $targetOA > 0 ? ($realOA / $targetOA) * 100 : 0,
            ];
        }

        $optCabang = Sales::select('city')->distinct()->whereNotNull('city')->pluck('city');

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan'       => $laporan,
            'listSuppliers' => $listSuppliers, // Kirim daftar kolom supplier
            'matrixSupplier'=> $matrixSupplier, // Kirim data penjualan per supplier
            'optCabang'     => $optCabang
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}