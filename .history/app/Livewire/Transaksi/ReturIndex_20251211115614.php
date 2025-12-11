<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Transaksi\Retur;
use App\Services\Import\ReturImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ReturIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $startDate;
    public $endDate;
    public $filterCabang = []; // Multi-select array

    // MODAL
    public $isImportOpen = false;
    public $file;
    public $resetData = false;
    
    // DETAIL MODAL
    public $isDetailOpen = false;
    public $detailItems = [];
    public $selectedRetur;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang']);
        $this->resetPage();
    }

    // --- LOGIC DETAIL RETUR ---
    public function openDetail($no_retur)
    {
        $this->selectedRetur = $no_retur;
        // Ambil item detail (hanya kolom yang diperlukan)
        $this->detailItems = Retur::where('no_retur', $no_retur)
            ->select('kode_item', 'nama_item', 'qty', 'satuan', 'total_grand')
            ->get();
        $this->isDetailOpen = true;
    }

    public function closeDetail()
    {
        $this->isDetailOpen = false;
        $this->detailItems = [];
    }

    public function render()
    {
        $query = Retur::query();

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }

        // 2. Date Filter
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_retur', [$this->startDate, $this->endDate]);
        }

        // 3. Cabang Filter
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        // --- SUMMARY STATS (HITUNG GLOBAL) ---
        $summary = [
            'total_nilai'  => (clone $query)->sum('total_grand'),
            'total_faktur' => (clone $query)->distinct('no_retur')->count('no_retur'),
            'total_items'  => (clone $query)->count(),
        ];

        // --- GROUPING TABLE ---
        $returs = $query
            ->select(
                'no_retur', 'tgl_retur', 'nama_pelanggan', 'sales_name', 'cabang',
                DB::raw('MIN(id) as id'),
                DB::raw('SUM(total_grand) as total_retur'),
                DB::raw('COUNT(*) as total_items')
            )
            ->groupBy('no_retur', 'tgl_retur', 'nama_pelanggan', 'sales_name', 'cabang')
            ->orderBy('tgl_retur', 'desc')
            ->paginate(10);

        $optCabang = Cache::remember('opt_cabang_retur', 3600, fn() => 
            Retur::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang')
        );

        return view('livewire.transaksi.retur-index', compact('returs', 'optCabang', 'summary'))
            ->layout('layouts.app', ['header' => 'Transaksi Retur']);
    }

    // --- IMPORT LOGIC (BIG DATA READY) ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }

    public function import() 
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);

        // 1. Lock Process
        $lock = Cache::lock('importing_retur', 600); // Lock 10 menit
        if (!$lock->get()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Import sedang berjalan. Tunggu sebentar!']);
            return;
        }

        // 2. Setup PHP
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            $path = $this->file->store('temp-import', 'local');
            // 3. Fix Windows Path
            $fullPath = Storage::disk('local')->path($path);

            // 4. Run Service
            $stats = (new ReturImportService)->handle($fullPath, $this->resetData); 
            $count = $stats['processed'] ?? 0;
            $skipped = $stats['skipped_empty'] ?? 0;

            // 5. Cleanup
            if(Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            Cache::forget('opt_cabang_retur');
            $this->closeImportModal();
            
            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => "BERHASIL! Masuk: " . number_format($count) . " baris. (Skip: $skipped)"
            ]);

        } catch (\Exception $e) {
            if(isset($path) && Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        } finally {
            $lock->release();
        }
    }

    // --- DELETE LOGIC (GROUP) ---
    public function delete($no_retur) {
        Retur::where('no_retur', $no_retur)->delete();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Faktur Retur berhasil dihapus']);
    }
}