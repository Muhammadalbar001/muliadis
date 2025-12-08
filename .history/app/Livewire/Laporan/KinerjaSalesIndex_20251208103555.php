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
    public $bulan;
    public $filterCabang = '';

    public function mount()
    {
        $this->bulan = date('Y-m');
    }

    public function render()
    {
        $date = Carbon::parse($this->bulan . '-01');
        $start = $date->startOfMonth()->format('Y-m-d');
        $end   = $date->endOfMonth()->format('Y-m-d');

        // 1. Ambil Master Sales (Filtered)
        $salesQuery = Sales::query();
        if ($this->filterCabang) {
            $salesQuery->where('city', $this->filterCabang);
        }
        $allSales = $salesQuery->orderBy('sales_name')->get();

        // ---------------------------------------------------------
        // TEKNIK EAGER LOADING / GROUP BY (Hanya 3 Query Total)
        // ---------------------------------------------------------

        // A. Ambil Rekap Penjualan (IMS, OA, EC) dikelompokkan per Sales
        // Hasilnya berupa Array: ['Nama Sales' => [total_grand, count_oa, count_ec]]
        $salesStats = Penjualan::selectRaw("
                sales_name, 
                SUM(CAST(total_grand AS DECIMAL(20,2))) as total_ims,
                COUNT(DISTINCT kode_pelanggan) as total_oa,
                COUNT(DISTINCT trans_no) as total_ec
            ")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name'); // Jadikan nama sales sebagai key array

        // B. Ambil Rekap AR (Piutang) dikelompokkan per Sales
        $arStats = AccountReceivable::selectRaw("
                sales_name,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) <= 30 THEN CAST(nilai AS DECIMAL(20,2)) ELSE 0 END) as ar_lancar,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) > 30 THEN CAST(nilai AS DECIMAL(20,2)) ELSE 0 END) as ar_macet
            ")
            ->where('nilai', '>', 0)
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name');

        // ---------------------------------------------------------
        // MAPPING DATA DI PHP (Cepat & Ringan)
        // ---------------------------------------------------------
        
        $laporan = [];

        foreach ($allSales as $sales) {
            $name = $sales->sales_name;

            // Ambil data dari hasil query grup di atas (Gunakan Null Coalescing ?? 0 jika tidak ada data)
            $statJual = $salesStats->get($name);
            $statAr   = $arStats->get($name);

            // Sales Stats
            $targetIMS = (float) $sales->target_ims;
            $realIMS   = $statJual ? (float) $statJual->total_ims : 0;
            
            // AR Stats
            $arLancar  = $statAr ? (float) $statAr->ar_lancar : 0;
            $arMacet   = $statAr ? (float) $statAr->ar_macet : 0;
            $totalAR   = $arLancar + $arMacet;

            // Produktifitas
            $targetOA  = (float) $sales->target_oa;
            $realOA    = $statJual ? (int) $statJual->total_oa : 0;
            $ec        = $statJual ? (int) $statJual->total_ec : 0;

            $laporan[] = [
                'nama'         => $name,
                'cabang'       => $sales->city,
                // Penjualan
                'target_ims'   => $targetIMS,
                'real_ims'     => $realIMS,
                'persen_ims'   => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                // AR
                'ar_lancar'    => $arLancar,
                'ar_macet'     => $arMacet,
                'total_ar'     => $totalAR,
                'persen_macet' => $totalAR > 0 ? ($arMacet / $totalAR) * 100 : 0,
                // Produktifitas
                'target_oa'    => $targetOA,
                'real_oa'      => $realOA,
                'ec'           => $ec,
                'persen_oa'    => $targetOA > 0 ? ($realOA / $targetOA) * 100 : 0,
            ];
        }

        $optCabang = Sales::select('city')->distinct()->whereNotNull('city')->pluck('city');

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan'   => $laporan,
            'optCabang' => $optCabang
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}