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
        // Kita menggunakan CAST karena kolom di database bertipe VARCHAR/STRING
        
        $totalPenjualan = Penjualan::sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $totalRetur     = Retur::sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $totalAR        = AccountReceivable::sum(DB::raw('CAST(nilai AS DECIMAL(20,2))')); // Sisa Piutang
        $totalCollection= Collection::sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));

        // 2. CHART 1: PENJUALAN VS RETUR (PER BULAN - TAHUN INI)
        $salesChart = $this->getMonthlyData(new Penjualan, 'tgl_penjualan', 'total_grand');
        $returChart = $this->getMonthlyData(new Retur, 'tgl_retur', 'total_grand');

        // 3. CHART 2: AR VS COLLECTION (PER BULAN - TAHUN INI)
        // Untuk AR kita pakai tanggal invoice/penjualan sebagai acuan tren
        $arChart = $this->getMonthlyData(new AccountReceivable, 'tgl_penjualan', 'total_nilai'); 
        $colChart = $this->getMonthlyData(new Collection, 'tanggal', 'receive_amount');

        // 4. CHART 3: TOP 10 PRODUK (Berdasarkan Qty Terjual)
        $topProducts = Penjualan::select('nama_item', DB::raw('SUM(CAST(qty AS DECIMAL(15,2))) as total_qty'))
                        ->whereNotNull('nama_item')
                        ->where('nama_item', '!=', '')
                        ->groupBy('nama_item')
                        ->orderByDesc('total_qty')
                        ->limit(10)
                        ->get();

        // 5. DATA FOR VIEW
        return view('dashboard', [
            'totalPenjualan' => $totalPenjualan,
            'totalRetur'     => $totalRetur,
            'totalAR'        => $totalAR,
            'totalCollection'=> $totalCollection,
            
            // Data Grafik (Array 12 Bulan)
            'salesData'      => array_values($salesChart),
            'returData'      => array_values($returChart),
            'arData'         => array_values($arChart),
            'collectionData' => array_values($colChart),
            
            // Data Produk
            'topProductLabels' => $topProducts->pluck('nama_item'),
            'topProductData'   => $topProducts->pluck('total_qty'),
        ]);
    }

    /**
     * Helper untuk mengambil data per bulan (Jan-Des) tahun ini
     */
    private function getMonthlyData($model, $dateCol, $sumCol)
    {
        $year = date('Y');
        
        $data = $model::select(
                    DB::raw("MONTH($dateCol) as month"), 
                    DB::raw("SUM(CAST($sumCol AS DECIMAL(20,2))) as total")
                )
                ->whereYear($dateCol, $year)
                ->groupBy('month')
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