<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KinerjaSalesIndex extends Component
{
    // Filter
    public $bulan;
    public $filterCabang = '';
    
    // Tab Aktif (Default: Tab Penjualan)
    public $activeTab = 'penjualan'; 

    public function mount()
    {
        // Default ke bulan ini
        $this->bulan = date('Y-m');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        // 1. Setup Tanggal
        // Jika filter bulan kosong, pakai bulan ini
        $bulanPilih = $this->bulan ?: date('Y-m');
        $dateObj = Carbon::parse($bulanPilih . '-01');
        $start   = $dateObj->startOfMonth()->format('Y-m-d');
        $end     = $dateObj->endOfMonth()->format('Y-m-d');
        
        $selectedYear  = $dateObj->year;
        $selectedMonth = $dateObj->month;

        // 2. Ambil Master Sales (Induk Data)
        $salesQuery = Sales::query();
        if ($this->filterCabang) {
            $salesQuery->where('city', $this->filterCabang);
        }
        // Pastikan hanya sales aktif yang muncul agar tidak penuh sampah
        $allSales = $salesQuery->where('status', 'aktif')->orderBy('sales_name')->get();

        // 3. Tarik Data Transaksi (GROUP BY Sales Name)
        
        // A. PENJUALAN (Omzet & Outlet Active & Efektif Call)
        $salesStats = Penjualan::selectRaw("
                sales_name, 
                SUM(CAST(total_grand AS DECIMAL(20,2))) as total_ims,
                COUNT(DISTINCT kode_pelanggan) as total_oa,
                COUNT(DISTINCT trans_no) as total_ec
            ")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name'); // Key pakai nama sales

        // B. PIUTANG (AR) - AR tidak melihat bulan, tapi saldo saat ini
        $arStats = AccountReceivable::selectRaw("
                sales_name,
                SUM(nilai) as total_ar,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) <= 30 THEN nilai ELSE 0 END) as ar_lancar,
                SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) > 30 THEN nilai ELSE 0 END) as ar_macet
            ")
            ->where('nilai', '>', 0) // Hanya yg punya saldo
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name');

        // C. TARGET (Ambil dari tabel sales_targets)
        $targets = SalesTarget::where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->get()
            ->keyBy('sales_id');

        // D. PIVOT SUPPLIER (Untuk Tab Supplier)
        // Kita ambil Top 10 Supplier agar tabel tidak terlalu lebar
        $topSuppliers = Penjualan::select('supplier', DB::raw('SUM(CAST(total_grand AS DECIMAL(20,2))) as val'))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('supplier')
            ->orderByDesc('val')
            ->limit(10)
            ->pluck('supplier'); // Ambil nama saja

        // Ambil data penjualan per sales per supplier
        $pivotData = Penjualan::selectRaw("sales_name, supplier, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereIn('supplier', $topSuppliers)
            ->groupBy('sales_name', 'supplier')
            ->get();

        // Susun ke Array Matrix: $matrix['NamaSales']['NamaSupplier'] = Rp...
        $matrixSupplier = [];
        foreach ($pivotData as $p) {
            $matrixSupplier[$p->sales_name][$p->supplier] = $p->total;
        }

        // 4. MAPPING DATA AKHIR (Gabungkan semua data ke Master Sales)
        $laporan = [];

        foreach ($allSales as $sales) {
            $name = $sales->sales_name;

            // Ambil Target
            $t = $targets->get($sales->id); 
            $t_ims = $t ? (float)$t->target_ims : 0; // Pastikan nama kolom di DB sales_targets benar: target_ims atau target_value? sesuaikan
            $t_oa  = $t ? (int)$t->target_oa : 0;
            $t_ec  = 0; // Target EC belum ada di tabel, default 0

            // Ambil Realisasi Jual
            // Gunakan pencocokan nama yang aman
            $stat = $salesStats->get($name); 
            $r_ims = $stat ? (float)$stat->total_ims : 0;
            $r_oa  = $stat ? (int)$stat->total_oa : 0;
            $r_ec  = $stat ? (int)$stat->total_ec : 0;

            // Ambil Realisasi AR
            $ar = $arStats->get($name);
            $ar_tot = $ar ? (float)$ar->total_ar : 0;
            $ar_reg = $ar ? (float)$ar->ar_lancar : 0;
            $ar_bad = $ar ? (float)$ar->ar_macet : 0;

            $laporan[] = [
                'nama' => $name,
                'cabang' => $sales->city,
                
                // Tab Penjualan
                't_ims' => $t_ims,
                'r_ims' => $r_ims,
                'p_ims' => $t_ims > 0 ? ($r_ims / $t_ims) * 100 : 0,

                // Tab Produktifitas
                't_oa' => $t_oa,
                'r_oa' => $r_oa,
                'p_oa' => $t_oa > 0 ? ($r_oa / $t_oa) * 100 : 0,
                'r_ec' => $r_ec,

                // Tab AR
                'ar_tot' => $ar_tot,
                'ar_reg' => $ar_reg,
                'ar_bad' => $ar_bad,
                'p_bad'  => $ar_tot > 0 ? ($ar_bad / $ar_tot) * 100 : 0,
            ];
        }

        // Urutkan berdasarkan Ranking Omzet tertinggi
        $laporan = collect($laporan)->sortByDesc('p_ims')->values();

        // Opsi Cabang untuk Filter
        $optCabang = Sales::select('city')->distinct()->whereNotNull('city')->pluck('city');

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan' => $laporan,
            'optCabang' => $optCabang,
            'topSuppliers' => $topSuppliers,
            'matrixSupplier' => $matrixSupplier
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}