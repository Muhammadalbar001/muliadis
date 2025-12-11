<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Keuangan\Collection;
use App\Services\Import\CollectionImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CollectionIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $startDate;
    public $endDate;
    public $filterCabang = [];
    public $filterSales = [];

    // Modal Import
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // Modal Detail
    public $isDetailOpen = false;
    public $detailItems = [];
    public $selectedBukti;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'filterSales']);
        $this->resetPage();
    }

    // --- LOGIC DETAIL ---
    public function openDetail($receive_no)
    {
        $this->selectedBukti = $receive_no;
        $this->detailItems = Collection::where('receive_no', $receive_no)->get();
        $this->isDetailOpen = true;
    }

    public function closeDetail()
    {
        $this->isDetailOpen = false;
        $this->detailItems = [];
    }

    public function render()
    {
        $query = Collection::query();

        // 1. Search (Perbaiki nama_pelanggan -> outlet_name)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('receive_no', 'like', '%'.$this->search.'%')
                  ->orWhere('outlet_name', 'like', '%'.$this->search.'%'); // PERBAIKAN DISINI
            });
        }

        // 2. Date Range
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        // 3. Filters
        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);
        if (!empty($this->filterSales)) $query->whereIn('sales_name', $this->filterSales);

        // --- SUMMARY STATS ---
        $summary = [
            'total_cair'   => (clone $query)->sum('receive_amount'), 
            'total_bukti'  => (clone $query)->distinct('receive_no')->count('receive_no'),
            'total_faktur' => (clone $query)->count(), 
        ];

        // --- GROUPING TABLE ---
        // Ganti nama_pelanggan -> outlet_name di SELECT dan GROUP BY
        $collections = $query
            ->select(
                'receive_no', 'tanggal', 'outlet_name', 'sales_name', 'cabang', // PERBAIKAN DISINI
                DB::raw('MIN(id) as id'),
                DB::raw('SUM(receive_amount) as total_bayar'),
                DB::raw('COUNT(*) as total_faktur')
            )
            ->groupBy('receive_no', 'tanggal', 'outlet_name', 'sales_name', 'cabang') // PERBAIKAN DISINI
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        $optCabang = Cache::remember('opt_coll_cabang', 3600, fn() => Collection::select('cabang')->distinct()->pluck('cabang'));
        $optSales = Cache::remember('opt_coll_sales', 3600, fn() => Collection::select('sales_name')->distinct()->pluck('sales_name'));

        return view('livewire.transaksi.collection-index', compact('collections', 'optCabang', 'optSales', 'summary'))
            ->layout('layouts.app', ['header' => 'Transaksi Collection']);
    }

    // --- IMPORT LOGIC ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() 
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);

        $lock = Cache::lock('importing_coll', 600);
        if (!$lock->get()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Import sedang berjalan. Tunggu!']);
            return;
        }

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            $path = $this->file->store('temp-import', 'local');
            $fullPath = Storage::disk('local')->path($path);

            $stats = (new CollectionImportService)->handle($fullPath, $this->resetData); 
            $count = $stats['processed'] ?? 0;
            $skipped = $stats['skipped_empty'] ?? 0;

            if(Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            Cache::forget('opt_coll_cabang');
            Cache::forget('opt_coll_sales');
            $this->closeImportModal();
            
            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => "BERHASIL! Masuk: " . number_format($count) . " Data Pelunasan. (Skip: $skipped)"
            ]);

        } catch (\Exception $e) {
            if(isset($path) && Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        } finally {
            $lock->release();
        }
    }

    public function delete($receive_no) {
        Collection::where('receive_no', $receive_no)->delete();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Collection dihapus']);
    }
}