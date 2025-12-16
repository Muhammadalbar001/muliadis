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
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\SimpleExcel\SimpleExcelWriter;

class KinerjaSalesIndex extends Component
{
    use WithPagination;

    public $bulan;
    public $filterCabang = []; // Multi-select array
    public $filterDivisi = []; // Multi-select array
    public $activeTab = 'penjualan'; 

    public function mount() { $this->bulan = date('Y-m'); }
    
    // Reset pagination when filters change
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterDivisi() { $this->resetPage(); } 
    public function updatedBulan() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['filterCabang', 'filterDivisi']);
        $this->bulan = date('Y-m');
        $this->resetPage();
    }

    public function export()
    {
        $data = $this->getDataLaporan(); 
        $laporan = $data['laporan'];
        $suppliers = $data['topSuppliers']; 
        $matrix = $data['matrixSupplier'];

        $writer = SimpleExcelWriter::streamDownload('Rapor_Kinerja_Sales_' . $this->bulan . '.xlsx');

        // Custom Header for Excel based on your requested structure
        $header = [
            'Nama Sales', 'Cabang', 'Divisi',
            'Target (Rp)', 'Pencapaian (Rp)', 'Ach (%)', // Penjualan
            'AR Reguler', 'AR > 30 Hari', '% Macet',     // AR
            'Outlet Aktif', 'Efektif Call'               // Produktifitas
        ];

        // Add Supplier Headers dynamically
        foreach($suppliers as $supp) {
            $header[] = $supp;
        }

        $writer->addHeader($header);

        foreach ($laporan as $row) {
            $rowData = [
                $row['nama'], $row['cabang'], $row['divisi'],
                $row['target_ims'], $row['real_ims'], $row['persen_ims'],
                $row['ar_reguler'], $row['ar_macet'], $row['ar_persen_macet'],
                $row['real_oa'], $row['ec'],
            ];

            // Add Supplier Data
            foreach($suppliers as $supp) {
                $val = $matrix[$row['nama']][$supp] ?? 0;
                $rowData[] = $val;
            }

            $writer->addRow($rowData);
        }

        return $writer->toBrowser();
    }

    private function getDataLaporan()
    {
        $bulanPilih = $this->bulan ?: date('Y-m');
        $dateObj = Carbon::parse($bulanPilih . '-01');
        $start   = $dateObj->startOfMonth()->format('Y-m-d');
        $end     = $dateObj->endOfMonth()->format('Y-m-d');
        $selectedYear  = $dateObj->year;
        $selectedMonth = $dateObj->month;

        // 1. Get List of Active Sales matching filters
        $salesQuery = Sales::query();
        if (!empty($this->filterCabang)) $salesQuery->whereIn('city', $this->filterCabang);
        if (!empty($this->filterDivisi)) $salesQuery->whereIn('divisi', $this->filterDivisi);
        // Filter active sales or those with transactions
        $salesQuery->where(function($q) {
             $q->where('status', 'Active')->orWhere('status', 'aktif');
        });
        $allSales = $salesQuery->orderBy('sales_name')->get();

        // 2. Fetch Data Sources
        
        // Targets
        $targets = SalesTarget::where('year', $selectedYear)->where('month', $selectedMonth)->get()->keyBy('sales_id');

        // Sales Transactions (Penjualan)
        // Note: Using REPLACE to handle potential formatting issues in DB if columns are VARCHAR
        $salesStats = Penjualan::selectRaw("sales_name, SUM(total_grand) as total_ims, COUNT(DISTINCT kode_pelanggan) as total_oa, COUNT(DISTINCT trans_no) as total_ec")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name');

        // Accounts Receivable (AR)
        // Assuming 'nilai' is the amount and 'umur_piutang' is the age in days
        $arStats = AccountReceivable::selectRaw("sales_name, 
            SUM(nilai) as total_ar, 
            SUM(CASE WHEN umur_piutang <= 30 THEN nilai ELSE 0 END) as ar_lancar, 
            SUM(CASE WHEN umur_piutang > 30 THEN nilai ELSE 0 END) as ar_macet")
            ->groupBy('sales_name')
            ->get()
            ->keyBy('sales_name');

        // Suppliers Pivot (Top Suppliers by Value)
        $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(total_grand) as val"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereNotNull('supplier')
            ->groupBy('supplier')
            ->orderByDesc('val')
            ->pluck('supplier');

        // Matrix Data for Supplier Tab
        $rawPivot = Penjualan::selectRaw("sales_name, supplier, SUM(total_grand) as total")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereIn('supplier', $topSuppliers)
            ->groupBy('sales_name', 'supplier')
            ->get();

        $matrixSupplier = [];
        foreach ($rawPivot as $p) { $matrixSupplier[$p->sales_name][$p->supplier] = $p->total; }

        // 3. Compile Data per Salesman
        $laporan = [];
        foreach ($allSales as $sales) {
            $name = $sales->sales_name;
            
            // Target Data
            $t = $targets->get($sales->id);
            $targetIMS = $t ? (float)$t->target_ims : 0;

            // Sales Data
            $stat = $salesStats->get($name) ?? $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            $realIMS = $stat ? (float)$stat->total_ims : 0;
            $realOA  = $stat ? (int)$stat->total_oa : 0;
            $ec      = $stat ? (int)$stat->total_ec : 0;

            // AR Data
            $ar = $arStats->get($name) ?? $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            $arTotal  = $ar ? (float)$ar->total_ar : 0;
            $arLancar = $ar ? (float)$ar->ar_lancar : 0;
            $arMacet  = $ar ? (float)$ar->ar_macet : 0;
            
            $laporan[] = [
                'nama'         => $name,
                'cabang'       => $sales->city,
                'divisi'       => $sales->divisi,
                // Penjualan
                'target_ims'   => $targetIMS,
                'real_ims'     => $realIMS,
                'persen_ims'   => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                'defisit'      => $realIMS < $targetIMS ? $targetIMS - $realIMS : 0,
                // AR
                'ar_total'     => $arTotal,
                'ar_lancar'    => $arLancar, // Reguler
                'ar_macet'     => $arMacet,  // > 30 Hari
                'ar_persen_macet' => $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0,
                // Produktifitas
                'real_oa'      => $realOA,
                'ec'           => $ec,
            ];
        }

        return [
            // Sort by Achievement % Descending
            'laporan' => collect($laporan)->sortByDesc('persen_ims')->values(),
            'topSuppliers' => $topSuppliers,
            'matrixSupplier' => $matrixSupplier
        ];
    }

    public function formatCompact($val)
    {
        if ($val >= 1000000000) return number_format($val / 1000000000, 2, ',', '.') . ' M';
        if ($val >= 1000000) return number_format($val / 1000000, 1, ',', '.') . ' Jt';
        return number_format($val, 0, ',', '.');
    }

    public function render()
    {
        $data = $this->getDataLaporan();
        $laporanCollection = $data['laporan'];

        // Global Summary for Top Cards
        $globalSummary = [
            'total_target' => $laporanCollection->sum('target_ims'),
            'total_real'   => $laporanCollection->sum('real_ims'),
            'total_ar'     => $laporanCollection->sum('ar_total'),
            'total_macet'  => $laporanCollection->sum('ar_macet'),
        ];

        // Manual Pagination for Collection
        $perPage = 50;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $laporanCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new LengthAwarePaginator($currentItems, count($laporanCollection), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query()
        ]);

        // Filter Options from DB
        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));
        $optDivisi = Cache::remember('opt_sales_divisi', 3600, fn() => Sales::select('divisi')->distinct()->whereNotNull('divisi')->orderBy('divisi')->pluck('divisi'));

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan' => $paginatedItems,
            'globalSummary' => $globalSummary,
            'optCabang' => $optCabang,
            'optDivisi' => $optDivisi,
            'topSuppliers' => $data['topSuppliers'],
            'matrixSupplier' => $data['matrixSupplier']
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}