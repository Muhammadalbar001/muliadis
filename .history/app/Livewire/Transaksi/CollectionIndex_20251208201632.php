<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Keuangan\Collection;
use App\Services\Import\CollectionImportService;
use Illuminate\Support\Facades\Cache;

class CollectionIndex extends Component
{
    use WithPagination, WithFileUploads;

    // Filter
    public $search = '';
    public $startDate;
    public $endDate;
    
    // Multi-Select Filters (Array)
    public $filterCabang = [];
    public $filterSales = [];

    // Modal
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // Reset Halaman saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }

    // Tombol Reset
    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'filterSales']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Collection::query();

        // 1. Search (No Bukti, Toko)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_bukti', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%');
            });
        }

        // 2. Date Range
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        // 3. Multi Filters
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }
        if (!empty($this->filterSales)) {
            $query->whereIn('sales_name', $this->filterSales);
        }

        $collections = $query->orderBy('tanggal', 'desc')->paginate(10);

        // Options (Cached 1 Jam)
        $optCabang = Cache::remember('opt_coll_cabang', 3600, fn() => Collection::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales = Cache::remember('opt_coll_sales', 3600, fn() => Collection::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.transaksi.collection-index', compact('collections', 'optCabang', 'optSales'))
            ->layout('layouts.app', ['header' => 'Transaksi Collection']);
    }

    // ... (Fungsi Import & Delete biarkan sama) ...
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() {
        $this->validate(['file' => 'required|mimes:xlsx,xls']);
        try {
            $path = $this->file->store('temp');
            (new CollectionImportService)->handle(storage_path('app/'.$path)); 
            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => 'Data berhasil diimport']);
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id) {
        Collection::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Data dihapus']);
    }
}