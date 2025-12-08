<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
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

    public function mount()
    {
        $this->filterBulan = date('m');
        $this->filterTahun = date('Y');
    }

    public function updatedFilterBulan() { $this->resetPage(); }
    public function updatedFilterTahun() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }

    public function render()
    {
        // 1. Ambil Daftar Salesman Aktif
        $query = Sales::query()->where('status', 'aktif');

        if ($this->search) {
            $query->where('sales_name', 'like', '%' . $this->search . '%');
        }

        // PERBAIKAN: Ganti 'cabang' jadi 'city' sesuai database
        if (!empty($this->filterCabang)) {
            $query->whereIn('city', $this->filterCabang);
        }

        $salesmen = $query->get();

        // 2. Kalkulasi KPI
        $laporan = $salesmen->map(function ($sales) {
            
            // Ambil Target
            $target = SalesTarget::where('sales_id', $sales->id)
                ->where('month', (int)$this->filterBulan)
                ->where('year', (int)$this->filterTahun)
                ->first();

            $targetOmzet = $target->target_value ?? 0;
            $targetOa    = $target->target_oa ?? 0;

            // Hitung Realisasi
            $realisasiQuery = Penjualan::where('sales_name', $sales->sales_name)
                ->whereMonth('tgl_penjualan', $this->filterBulan)
                ->whereYear('tgl_penjualan', $this->filterTahun);

            $realOmzet = $realisasiQuery->sum('total_grand');
            $realOa    = $realisasiQuery->distinct('code_customer')->count('code_customer');

            // Hitung Achievement
            $achOmzet = $targetOmzet > 0 ? ($realOmzet / $targetOmzet) * 100 : 0;
            $achOa    = $targetOa > 0 ? ($realOa / $targetOa) * 100 : 0;

            $score = ($achOmzet + $achOa) / 2;

            return [
                'id'            => $sales->id,
                'sales_name'    => $sales->sales_name,
                
                // PERBAIKAN: Mapping 'cabang' di view diambil dari kolom 'city'
                'cabang'        => $sales->city, 
                
                'target_omzet'  => $targetOmzet,
                'real_omzet'    => $realOmzet,
                'ach_omzet'     => $achOmzet,
                
                'target_oa'     => $targetOa,
                'real_oa'       => $realOa,
                'ach_oa'        => $achOa,

                'score'         => $score
            ];
        });

        // 3. Sorting
        $sortedLaporan = $laporan->sortByDesc('ach_omzet')->values();

        // 4. Pagination Manual
        $perPage = 20;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentItems = $sortedLaporan->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($sortedLaporan), $perPage);

        // 5. Options Cabang (PERBAIKAN: Ambil dari kolom 'city')
        $optCabang = Cache::remember('opt_sales_city', 3600, fn() => 
            Sales::select('city')->distinct()->whereNotNull('city')->pluck('city')
        );

        // 6. Summary
        $summary = [
            'total_target' => $laporan->sum('target_omzet'),
            'total_real'   => $laporan->sum('real_omzet'),
            'avg_ach'      => $laporan->avg('ach_omzet'),
            'best_sales'   => $sortedLaporan->first()['sales_name'] ?? '-'
        ];

        return view('livewire.laporan.kinerja-sales-index', [
            'data' => $paginatedItems,
            'optCabang' => $optCabang,
            'summary' => $summary
        ])->layout('layouts.app', ['header' => 'Rapor Kinerja Sales']);
    }
}