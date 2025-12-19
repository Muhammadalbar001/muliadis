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
    public $search = ''; // Fitur Pencarian
    public $minNominal = 50000; 
    public $filterCabang = []; 
    public $filterDivisi = []; 
    public $activeTab = 'penjualan'; 

    public function mount() { 
        $this->bulan = date('Y-m'); 
    }
    
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterDivisi() { $this->resetPage(); } 
    public function updatedBulan() { $this->resetPage(); }
    public function updatedMinNominal() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['filterCabang', 'filterDivisi', 'minNominal', 'search']);
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

        $header = [
            'Nama Sales', 'Cabang', 'Divisi',
            'Target (Rp)', 'Pencapaian (Rp)', 'Ach (%)',
            'AR Reguler', 'AR > 30 Hari', '% Macet',
            'Outlet Aktif', 'Efektif Call (>' . number_format((float)($this->minNominal ?: 0), 0, ',', '.') . ')',
            'Jml Supplier Terjual', 'Total Omzet Supplier'
        ];

        foreach($suppliers as $supp) { $header[] = $supp; }
        $writer->addHeader($header);

        foreach ($laporan as $row) {
            $rowData = [
                $row['nama'], $row['cabang'], $row['divisi'],
                $row['target_ims'], $row['real_ims'], $row['persen_ims'],
                $row['ar_lancar'], $row['ar_macet'], $row['ar_persen_macet'],
                $row['real_oa'], $row['ec'],
                $row['jml_supplier'], $row['total_supplier_val']
            ];
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
        $currentMin = (float) ($this->minNominal ?: 0);

        $salesQuery = Sales::query();
        if ($this->search) {
            $salesQuery->where('sales_name', 'like', '%' . $this->search . '%');
        }
        if (!empty($this->filterCabang)) $salesQuery->whereIn('city', $this->filterCabang);
        if (!empty($this->filterDivisi)) $salesQuery->whereIn('divisi', $this->filterDivisi);
        $salesQuery->whereIn('status', ['Active', 'aktif', 'Aktif']);
        $allSales = $salesQuery->orderBy('sales_name')->get();

        $targets = SalesTarget::where('year', $dateObj->year)->where('month', $dateObj->month)->get()->keyBy('sales_id');

        // Logic Subquery per nota untuk EC
        $subQuery = DB::table('penjualans')
            ->select('sales_name', 'trans_no', 'kode_pelanggan', DB::raw("SUM(total_grand) as total_per_nota"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->groupBy('sales_name', 'trans_no', 'kode_pelanggan');

        $salesStats = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery)
            ->selectRaw("
                sales_name, 
                SUM(total_per_nota) as total_ims, 
                COUNT(DISTINCT kode_pelanggan) as total_oa, 
                COUNT(DISTINCT CASE WHEN total_per_nota >= ? THEN trans_no END) as total_ec", [$currentMin])
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        $arStats = AccountReceivable::selectRaw("sales_name, 
            SUM(nilai) as total_ar, 
            SUM(CASE WHEN umur_piutang <= 30 THEN nilai ELSE 0 END) as ar_lancar, 
            SUM(CASE WHEN umur_piutang > 30 THEN nilai ELSE 0 END) as ar_macet")
            ->groupBy('sales_name')->get()->keyBy('sales_name');

        $topSuppliers = Penjualan::select('supplier', DB::raw("SUM(total_grand) as val"))
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereNotNull('supplier')->groupBy('supplier')->orderByDesc('val')->limit(10)->pluck('supplier');

        $rawPivot = Penjualan::selectRaw("sales_name, supplier, SUM(total_grand) as total")
            ->whereBetween('tgl_penjualan', [$start, $end])
            ->whereIn('supplier', $topSuppliers)->groupBy('sales_name', 'supplier')->get();

        $matrixSupplier = [];
        foreach ($rawPivot as $p) { $matrixSupplier[$p->sales_name][$p->supplier] = $p->total; }

        $laporan = [];
        foreach ($allSales as $sales) {
            $name = $sales->sales_name;
            $t = $targets->get($sales->id);
            $stat = $salesStats->get($name) ?? $salesStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            $ar = $arStats->get($name) ?? $arStats->first(fn($i) => strtoupper($i->sales_name) === strtoupper($name));
            
            $targetIMS = $t ? (float)$t->target_ims : 0;
            $realIMS = $stat ? (float)$stat->total_ims : 0;
            $arTotal = $ar ? (float)$ar->total_ar : 0;
            $arMacet = $ar ? (float)$ar->ar_macet : 0;

            // Hitung metrik supplier baru
            $countSupplied = isset($matrixSupplier[$name]) ? count($matrixSupplier[$name]) : 0;
            $sumSupplied = isset($matrixSupplier[$name]) ? array_sum($matrixSupplier[$name]) : 0;

            $laporan[] = [
                'nama' => $name,
                'cabang' => $sales->city,
                'divisi' => $sales->divisi,
                'target_ims' => $targetIMS,
                'real_ims' => $realIMS,
                'persen_ims' => $targetIMS > 0 ? ($realIMS / $targetIMS) * 100 : 0,
                'ar_total' => $arTotal,
                'ar_lancar' => $ar ? (float)$ar->ar_lancar : 0,
                'ar_macet' => $arMacet,
                'ar_persen_macet' => $arTotal > 0 ? ($arMacet / $arTotal) * 100 : 0,
                'real_oa' => $stat ? (int)$stat->total_oa : 0,
                'ec' => $stat ? (int)$stat->total_ec : 0,
                'jml_supplier' => $countSupplied,
                'total_supplier_val' => $sumSupplied,
            ];
        }

        return [
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

        $perPage = 50;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $laporanCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new LengthAwarePaginator($currentItems, count($laporanCollection), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query()
        ]);

        return view('livewire.laporan.kinerja-sales-index', [
            'laporan' => $paginatedItems,
            'globalSummary' => [
                'total_target' => $laporanCollection->sum('target_ims'),
                'total_real' => $laporanCollection->sum('real_ims'),
                'total_ar' => $laporanCollection->sum('ar_total'),
                'total_macet' => $laporanCollection->sum('ar_macet'),
            ],
            'optCabang' => Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city')),
            'optDivisi' => Cache::remember('opt_sales_divisi', 3600, fn() => Sales::select('divisi')->distinct()->whereNotNull('divisi')->orderBy('divisi')->pluck('divisi')),
            'topSuppliers' => $data['topSuppliers'],
            'matrixSupplier' => $data['matrixSupplier']
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}