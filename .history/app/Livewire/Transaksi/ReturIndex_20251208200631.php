<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Transaksi\Retur;
use App\Services\Import\ReturImportService;
use Illuminate\Support\Facades\Cache;

class ReturIndex extends Component
{
    use WithPagination, WithFileUploads;

    // Filter
    public $search = '';
    public $startDate;
    public $endDate;
    
    // Multi-Select Filters
    public $filterCabang = [];
    public $filterSales = [];

    // Modal
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'filterSales']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Retur::query();

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%');
            });
        }

        // 2. Date Range
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_retur', [$this->startDate, $this->endDate]);
        }

        // 3. Multi Filters
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }
        if (!empty($this->filterSales)) {
            $query->whereIn('sales_name', $this->filterSales);
        }

        $returs = $query->orderBy('tgl_retur', 'desc')->paginate(10);

        // Opsi untuk Dropdown (Cached)
        $optCabang = Cache::remember('opt_retur_cabang', 3600, fn() => Retur::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales = Cache::remember('opt_retur_sales', 3600, fn() => Retur::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.transaksi.retur-index', compact('returs', 'optCabang', 'optSales'))
            ->layout('layouts.app', ['header' => 'Transaksi Retur']);
    }

    // ... (Fungsi Import & Delete TETAP SAMA seperti sebelumnya) ...
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() {
        $this->validate(['file' => 'required|mimes:xlsx,xls']);
        try {
            $path = $this->file->store('temp');
            (new ReturImportService)->handle(storage_path('app/'.$path)); // Tambah reset logic jika perlu
            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => 'Data Retur berhasil diimport']);
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id) {
        Retur::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Data dihapus']);
    }
}