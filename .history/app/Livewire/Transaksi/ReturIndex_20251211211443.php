<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Transaksi\Retur;
use App\Services\Import\ReturImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ReturIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $startDate;
    public $endDate;
    public $filterCabang = [];

    // Modal Import
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Retur::query();

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_item', 'like', '%'.$this->search.'%');
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

        // --- SUMMARY STATS ---
        $summary = [
            'total_nilai'  => (clone $query)->sum('total_grand'),
            'total_faktur' => (clone $query)->distinct('no_retur')->count('no_retur'),
            'total_items'  => (clone $query)->count(),
        ];

        // --- FLAT TABLE (TANPA GROUPING) ---
        $returs = $query->orderBy('tgl_retur', 'desc')->paginate(10);

        $optCabang = Cache::remember('opt_cabang_retur', 3600, fn() => 
            Retur::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang')
        );

        return view('livewire.transaksi.retur-index', compact('returs', 'optCabang', 'summary'))
            ->layout('layouts.app', ['header' => 'Transaksi Retur']);
    }

    // --- IMPORT & DELETE ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }

    public function import() 
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);

        $lock = Cache::lock('importing_retur', 600);
        if (!$lock->get()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Import sedang berjalan. Tunggu!']);
            return;
        }

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            $path = $this->file->store('temp-import', 'local');
            $fullPath = Storage::disk('local')->path($path);

            $stats = (new ReturImportService)->handle($fullPath, $this->resetData);
            $count = $stats['processed'] ?? 0;

            if(Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            Cache::forget('opt_cabang_retur');
            $this->closeImportModal();
            
            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => "Sukses! $count Data Retur masuk."
            ]);

        } catch (\Exception $e) {
            if(isset($path) && Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        } finally {
            $lock->release();
        }
    }

    public function delete($id) {
        Retur::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Retur dihapus']);
    }
}