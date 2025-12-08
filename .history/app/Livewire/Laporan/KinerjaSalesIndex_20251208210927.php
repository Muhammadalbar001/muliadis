<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class KinerjaSalesIndex extends Component
{
    use WithPagination;

    // Filter
    public $search = '';
    public $filterBulan;
    public $filterTahun;
    public $filterCabang = [];

    // Tab Aktif (Default)
    public $activeTab = 'penjualan'; 

    public function mount()
    {
        $this->filterBulan = date('m');
        $this->filterTahun = date('Y');
    }

    public function updatedFilterBulan() { $this->resetPage(); }
    public function updatedFilterTahun() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }

    // Fungsi Ganti Tab
    public function setTab($tab) { $this->activeTab = $tab; }

    public function render()
    {
        // 1. Base Query Salesman
        $query = Sales::query()->where('status', 'aktif');
        if ($this->search) $query->where('sales_name', 'like', '%' . $this->search . '%');
        if (!empty($this->filterCabang)) $query->whereIn('city', $this->filterCabang);
        
        $salesmen = $query->get();

        // 2. Ambil Top 10 Supplier untuk Tab "By Supplier" (Agar tabel tidak terlalu panjang)
        $topSuppliers = Cache::remember('top_suppliers_'.$this->filterBulan, 3600, function() {
            return Penjualan::select('supplier', DB::raw('sum(total_grand) as total'))
                ->whereMonth('tgl_penjualan', $this->filterBulan)
                ->whereYear('tgl_penjualan', $this->filterTahun)
                ->groupBy('supplier')
                ->orderByDesc('total')
                ->limit(7) // Batasi 7 supplier terbesar
                ->pluck('supplier')
                ->toArray();
        });

        // 3. Kalkulasi Data Lengkap
        $laporan = $salesmen->map(function ($sales) use ($topSuppliers) {
            
            // --- A. DATA TARGET ---
            $target = SalesTarget::where('sales_id', $sales->id)
                ->where('month', (int)$this->filterBulan)
                ->where('year', (int)$this->filterTahun)
                ->first();

            $t_omzet = $target->target_value ?? 0;
            $t_oa    = $target->target_oa ?? 0;
            $t_ec    = $target->target_ec ?? 0; // Target Effective Call (Asumsi ada kolom ini)

            // --- B. DATA PENJUALAN (OMZET & OA & EC) ---
            $salesData = Penjualan::where('sales_name', $sales->sales_name)
                ->whereMonth('tgl_penjualan', $this->filterBulan)
                ->whereYear('tgl_penjualan', $this->filterTahun)
                ->get();

            $r_omzet = $salesData->sum('total_grand');
            $r_oa    = $salesData->unique('code_customer')->count();
            $r_ec    = $salesData->count(); // Realisasi EC = Jumlah Faktur (Proxy)

            // --- C. DATA PIUTANG (AR) ---
            // Ambil snapshot AR saat ini (tidak berdasarkan bulan, tapi saldo terakhir)
            $arData = AccountReceivable::where('sales_name', $sales->sales_name)->get();
            
            $ar_total   = $arData->sum('nilai');
            $ar_lancar  = $arData->where('umur_piutang', '<=', 30)->sum('nilai');
            $ar_macet   = $arData->where('umur_piutang', '>', 30)->sum('nilai');
            $ar_percent_macet = $ar_total > 0 ? ($ar_macet / $ar_total) * 100 : 0;

            // --- D. DATA PER SUPPLIER ---
            $suppData = [];
            foreach ($topSuppliers as $suppName) {
                $suppData[$suppName] = $salesData->where('supplier', $suppName)->sum('total_grand');
            }
            // Supplier Lainnya
            $suppData['Others'] = $salesData->whereNotIn('supplier', $topSuppliers)->sum('total_grand');

            return [
                'id'         => $sales->id,
                'sales_name' => $sales->sales_name,
                'cabang'     => $sales->city, // Ingat: Di DB namanya 'city'
                
                // 1. PENJUALAN
                't_omzet'    => $t_omzet,
                'r_omzet'    => $r_omzet,
                'ach_omzet'  => $t_omzet > 0 ? ($r_omzet / $t_omzet) * 100 : 0,

                // 2. AR
                'ar_total'   => $ar_total,
                'ar_lancar'  => $ar_lancar,
                'ar_macet'   => $ar_macet,
                'ar_pct'     => $ar_percent_macet,

                // 3. PRODUKTIFITAS
                't_oa'       => $t_oa,
                'r_oa'       => $r_oa,
                'ach_oa'     => $t_oa > 0 ? ($r_oa / $t_oa) * 100 : 0,
                't_ec'       => $t_ec,
                'r_ec'       => $r_ec,
                'ach_ec'     => $t_ec > 0 ? ($r_ec / $t_ec) * 100 : 0,

                // 4. SUPPLIER MATRIX
                'suppliers'  => $suppData
            ];
        });

        // Sorting default berdasarkan Omzet Achievement
        $data = $laporan->sortByDesc('ach_omzet')->values();
        
        // Manual Pagination
        $perPage = 20;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentItems = $data->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($data), $perPage);

        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->pluck('city'));

        return view('livewire.laporan.kinerja-sales-index', [
            'data' => $paginatedItems,
            'optCabang' => $optCabang,
            'topSuppliers' => $topSuppliers
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}