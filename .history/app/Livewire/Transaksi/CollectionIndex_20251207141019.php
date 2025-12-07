<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Keuangan\Collection;
use App\Services\Import\CollectionImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CollectionIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $filterCabang = [];
    public $filterPenagih = [];
    public $filterSales = [];

    public $isImportOpen = false;
    public $file;
    public $iteration = 1;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterPenagih() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->filterCabang = [];
        $this->filterPenagih = [];
        $this->filterSales = [];
        $this->resetPage();
    }

    public function render()
    {
        $query = Collection::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('receive_no', 'like', '%'.$this->search.'%')
                  ->orWhere('invoice_no', 'like', '%'.$this->search.'%')
                  ->orWhere('outlet_name', 'like', '%'.$this->search.'%');
            });
        }

        $query->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
              ->when(!empty($this->filterPenagih), fn($q) => $q->whereIn('penagih', $this->filterPenagih))
              ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales));

        $collections = $query->orderBy('tanggal', 'desc')->paginate(10);
        
        $optCabang  = Cache::remember('col_opt_cabang', 3600, fn() => Collection::select('cabang')->distinct()->whereNotNull('cabang')->orderBy('cabang')->pluck('cabang'));
        $optPenagih = Cache::remember('col_opt_penagih', 3600, fn() => Collection::select('penagih')->distinct()->whereNotNull('penagih')->orderBy('penagih')->pluck('penagih'));
        $optSales   = Cache::remember('col_opt_sales', 3600, fn() => Collection::select('sales_name')->distinct()->whereNotNull('sales_name')->orderBy('sales_name')->pluck('sales_name'));

        return view('livewire.transaksi.collection-index', [
            'collections' => $collections,
            'optCabang'   => $optCabang,
            'optPenagih'  => $optPenagih,
            'optSales'    => $optSales,
        ])->layout('layouts.app', ['header' => 'Data Collection (Pelunasan)']);
    }

    // Import & Delete Logic (Standard)
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; $this->iteration++; }
    public function import() {
        $this->validate(['file' => 'required|file|mimes:xlsx,csv,xls|max:102400']);
        $filename = null;
        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);
            $importService = new CollectionImportService();
            $stats = $importService->handle($fullPath);
            if (Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            $this->closeImportModal();
            Cache::forget('col_opt_cabang'); Cache::forget('col_opt_penagih'); Cache::forget('col_opt_sales');
            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => "Masuk: {$stats['processed']}"]);
        } catch (\Exception $e) {
            if ($filename) Storage::disk('local')->delete($filename);
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }
    public function delete($id) { Collection::destroy($id); $this->dispatch('show-toast', ['type'=>'success','title'=>'Dihapus','message'=>'Data dihapus']); }
}