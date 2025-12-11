<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\SimpleExcel\SimpleExcelWriter; // Tambahkan Ini

class KinerjaSalesIndex extends Component
{
    use WithPagination;

    public $bulan;
    public $filterCabang = '';
    public $filterDivisi = ''; 
    public $activeTab = 'penjualan'; 

    public function mount() { $this->bulan = date('Y-m'); }
    public function setTab($tab) { $this->activeTab = $tab; }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterDivisi() { $this->resetPage(); } 
    public function updatedBulan() { $this->resetPage(); }

    // --- 1. FITUR EXPORT EXCEL ---
    public function export()
    {
        $data = $this->getDataLaporan(); // Ambil data olahan
        $laporan = $data['laporan'];     // Array data sales

        $writer = SimpleExcelWriter::streamDownload('Rapor_Kinerja_Sales_' . $this->bulan . '.xlsx');

        foreach ($laporan as $row) {
            $writer->addRow([
                'Nama Sales'    => $row['nama'],
                'Cabang'        => $row['cabang'],
                
                // Penjualan
                'Target (Rp)'   => $row['target_ims'],
                'Realisasi (Rp)'=> $row['real_ims'],
                'Ach (%)'       => $row['persen_ims'],
                'Kurang (Rp)'   => $row['defisit'], // Kolom Baru
                
                // AR
                'Total AR'      => $row['ar_total'],
                'AR Lancar'     => $row['ar_lancar'],
                'AR Macet'      => $row['ar_macet'],
                'Rasio Macet %' => $row['persen_macet'],

                // Produktifitas
                'Target OA'     => $row['target_oa'],
                'Real OA'       => $row['real_oa'],
                'Ach OA %'      => $row['persen_oa'],
                'EC (Faktur)'   => $row['ec'],
            ]);
        }

        return $writer->toBrowser();
    }

    // --- 2. LOGIC PENGOLAHAN DATA (DIPISAH AGAR BISA DIPAKAI EXPORT) ---
    private function getDataLaporan()
    {
        $bulanPilih = $this->bulan ?: date('Y-m');
        $dateObj = Carbon::parse($bulanPilih . '-01');
        $start   = $dateObj->startOfMonth()->format('Y-m-d');
        $end     = $dateObj->endOfMonth()->format('Y-m-d');
        $selectedYear  = $dateObj->year;
        $selectedMonth = $dateObj->month;

        // Query Master Sales
        $salesQuery = Sales::query();
        if ($this->filterCabang) $salesQuery->where('city', $this->filterCabang);
        if ($this->filterDivisi) $salesQuery->where('divisi', $this->filterDivisi);
        $salesQuery->where(fn($q) => $q->where('status', 'Active')->orWhere('status', 'aktif'));
        $allSales = $salesQuery->orderBy('sales_name')->get();

        // Tarik Data Transaksi
        $targets = SalesTarget::where('year', $selectedYear)->where('month', $selectedMonth)->get()->keyBy('sales_id');

        $salesStats = Penjualan::selectRaw("sales_name, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_ims, COUNT(DISTINCT kode_pelanggan) as total_oa, COUNT(DISTINCT trans_no) as total_ec")
            ->whereBetween('tgl_penjualan', [$start, $end])->groupBy('sales_name')->get()->keyBy('sales_name');

        $arStats = AccountReceivable::selectRaw("sales_name, SUM(CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_ar, SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) <= 30 THEN CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2)) ELSE 0 END) as ar_lancar, SUM(CASE WHEN CAST(umur_piutang AS UNSIGNED) > 30 THEN CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2)) ELSE 0 END) as ar_macet")
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        // Pivot Supplier (Top 10)
        $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as val"))
            ->whereBetween('tgl_penjualan', [$start, $end])->whereNotNull('supplier')->groupBy('supplier')->orderByDesc('val')->limit(10)->pluck('supplier'); // Limit 10 biar tidak berat

        $rawPivot = Penjualan::selectRaw("sales_name, supplier, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")
            ->whereBetween('tgl_penjualan', [$start, $end])->whereIn('supplier', $topSuppliers)->groupBy('sales_name', 'supplier')->get();

        $matrixSupplier = [];
        foreach ($rawPivot as $p) { $matrixSupplier[$p->sales_name][$p->supplier] = $p->total; }

        $laporan = [];
        foreach ($allSales as $sales) {
            $name = $sales->sales_name;
            $t = $targets->get($sales->id);
            $targetIMS = $t ? (float)$t->target_ims : 0;
            $targetOA  = $t ? (int)$t->target_oa : 0;

            $stat = $salesStats->get($name) ?? $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            $realIMS = $stat ? (float)$stat->total_ims : 0;
            $realOA  = $stat ? (int)$stat->total_oa : 0;
            $ec      = $stat ? (int)$stat->total_ec : 0;

            $ar = $arStats->get($name) ?? $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            $arTotal  = $ar ? (float)$ar->total_ar : 0;
            $arLancar = $ar ? (float)$ar->ar_lancar : 0;
            $arMacet  = $ar ? (float)$ar->ar_macet : 0;
            
            $laporan[] = [
                'nama'         => $name,
                'cabang'       => $sales->city,
                'target_ims'   => $targetIMS,
                'real_ims'     => $realIMS,
                'persen_ims'   => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                'defisit'      => $realIMS < $targetIMS ? $targetIMS - $realIMS : 0, // Hitung Kurang
                'ar_total'     => $arTotal,
                'ar_lancar'    => $arLancar,
                'ar_macet'     => $arMacet,
                'persen_macet' => $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0,
                'target_oa'    => $targetOA,
                'real_oa'      => $realOA,
                'persen_oa'    => $targetOA > 0 ? ($realOA / $targetOA) * 100 : 0,
                'ec'           => $ec,
            ];
        }

        return [
            'laporan' => collect($laporan)->sortByDesc('persen_ims')->values(),
            'topSuppliers' => $topSuppliers,
            'matrixSupplier' => $matrixSupplier
        ];
    }

    public function render()
    {
        $data = $this->getDataLaporan();
        $laporanCollection = $data['laporan'];

        // 3. HITUNG SUMMARY GLOBAL
        $globalSummary = [
            'total_target' => $laporanCollection->sum('target_ims'),
            'total_real'   => $laporanCollection->sum('real_ims'),
            'total_ar'     => $laporanCollection->sum('ar_total'),
            'total_macet'  => $laporanCollection->sum('ar_macet'),
            'avg_ach'      => $laporanCollection->avg('persen_ims'),
        ];

        // Pagination Manual
        $perPage = 20;
        $currentPage = $this->getPage(); 
        $currentItems = $laporanCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new LengthAwarePaginator($currentItems, count($laporanCollection), $perPage, $currentPage, ['path' => request()->url(), 'query' => request()->query()]);

        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));
        $optDivisi = Cache::remember('opt_sales_divisi', 3600, fn() => Sales::select('divisi')->distinct()->whereNotNull('divisi')->orderBy('divisi')->pluck('divisi'));

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan' => $paginatedItems,
            'globalSummary' => $globalSummary, // Kirim ke View
            'optCabang' => $optCabang,
            'optDivisi' => $optDivisi,
            'topSuppliers' => $data['topSuppliers'],
            'matrixSupplier' => $data['matrixSupplier']
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}