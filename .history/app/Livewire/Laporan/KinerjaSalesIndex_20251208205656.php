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
        // Default ke Bulan & Tahun sekarang
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

        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        // Ambil data Salesman (Tanpa Pagination dulu untuk kalkulasi ranking)
        $salesmen = $query->get();

        // 2. Kalkulasi KPI per Salesman
        $laporan = $salesmen->map(function ($sales) {
            
            // A. Ambil Target (Dari Tabel SalesTarget)
            // Asumsi tabel sales_targets punya kolom: sales_id, month, year, target_value, target_oa
            $target = SalesTarget::where('sales_id', $sales->id)
                ->where('month', (int)$this->filterBulan)
                ->where('year', (int)$this->filterTahun)
                ->first();

            $targetOmzet = $target->target_value ?? 0;
            $targetOa    = $target->target_oa ?? 0;

            // B. Hitung Realisasi (Dari Tabel Penjualans)
            $realisasiQuery = Penjualan::where('sales_name', $sales->sales_name) // Atau sales_id jika ada relasi
                ->whereMonth('tgl_penjualan', $this->filterBulan)
                ->whereYear('tgl_penjualan', $this->filterTahun);

            $realOmzet = $realisasiQuery->sum('total_grand'); // Total Penjualan
            $realOa    = $realisasiQuery->distinct('code_customer')->count('code_customer'); // Jumlah Toko Unik

            // C. Hitung Persentase (Achievement)
            $achOmzet = $targetOmzet > 0 ? ($realOmzet / $targetOmzet) * 100 : 0;
            $achOa    = $targetOa > 0 ? ($realOa / $targetOa) * 100 : 0;

            // D. Tentukan Grade/Score Sederhana
            $score = ($achOmzet + $achOa) / 2; // Rata-rata pencapaian

            return [
                'id'            => $sales->id,
                'sales_name'    => $sales->sales_name,
                'cabang'        => $sales->cabang,
                
                'target_omzet'  => $targetOmzet,
                'real_omzet'    => $realOmzet,
                'ach_omzet'     => $achOmzet,
                
                'target_oa'     => $targetOa,
                'real_oa'       => $realOa,
                'ach_oa'        => $achOa,

                'score'         => $score
            ];
        });

        // 3. Sorting (Ranking Tertinggi ke Terendah berdasarkan Score Omzet)
        $sortedLaporan = $laporan->sortByDesc('ach_omzet')->values();

        // 4. Manual Pagination (Karena kita manipulasi Collection)
        $perPage = 20;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentItems = $sortedLaporan->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($sortedLaporan), $perPage);

        // 5. Options Cabang
        $optCabang = Cache::remember('opt_sales_cabang', 3600, fn() => Sales::select('cabang')->distinct()->pluck('cabang'));

        // 6. Ringkasan Global (Untuk Kartu Atas)
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