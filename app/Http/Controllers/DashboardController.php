<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Models
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use App\Models\Master\Produk;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATS CARDS (RINGKASAN)
        $totalPenjualan = Penjualan::sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $totalRetur     = Retur::sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $totalAR        = AccountReceivable::sum(DB::raw('CAST(nilai AS DECIMAL(20,2))')); 
        $totalCollection= Collection::sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));

        // 2. CHART DATA
        $salesChart = $this->getMonthlyData(new Penjualan, 'tgl_penjualan', 'total_grand');
        $returChart = $this->getMonthlyData(new Retur, 'tgl_retur', 'total_grand');
        $arChart    = $this->getMonthlyData(new AccountReceivable, 'tgl_penjualan', 'total_nilai'); 
        $colChart   = $this->getMonthlyData(new Collection, 'tanggal', 'receive_amount');

        // 3. TOP PRODUCTS
        $topProducts = Penjualan::select('nama_item', DB::raw('SUM(CAST(qty AS DECIMAL(15,2))) as total_qty'))
                        ->whereNotNull('nama_item')
                        ->where('nama_item', '!=', '')
                        ->groupBy('nama_item')
                        ->orderByDesc('total_qty')
                        ->limit(10)
                        ->get();

        return view('dashboard', [
            'totalPenjualan' => $totalPenjualan,
            'totalRetur'     => $totalRetur,
            'totalAR'        => $totalAR,
            'totalCollection'=> $totalCollection,
            'salesData'      => array_values($salesChart),
            'returData'      => array_values($returChart),
            'arData'         => array_values($arChart),
            'collectionData' => array_values($colChart),
            'topProductLabels' => $topProducts->pluck('nama_item'),
            'topProductData'   => $topProducts->pluck('total_qty'),
        ]);
    }

    /**
     * Helper untuk mengambil data per bulan (Jan-Des) tahun ini
     * PERBAIKAN: Menggunakan Group By Expression agar kompatibel dengan SQL Strict Mode
     */
    private function getMonthlyData($model, $dateCol, $sumCol)
    {
        $year = date('Y');
        
        $data = $model::select(
                    DB::raw("MONTH($dateCol) as month"), 
                    DB::raw("SUM(CAST($sumCol AS DECIMAL(20,2))) as total")
                )
                ->whereYear($dateCol, $year)
                ->groupBy(DB::raw("MONTH($dateCol)")) // <--- PERBAIKAN DISINI (Gunakan DB::raw)
                ->pluck('total', 'month')
                ->toArray();

        // Fill 0 for empty months
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = $data[$i] ?? 0;
        }

        return $result;
    }
}