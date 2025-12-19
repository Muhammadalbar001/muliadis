<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Keuangan\Collection;
use App\Services\Import\CollectionImportService;
use Illuminate\Support\Facades\DB;

class CollectionIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterCabang = [];
    public $filterPenagih = '';
    
    // Properti Hapus & Import
    public $deleteStartDate, $deleteEndDate;
    public $isImportOpen = false, $file, $resetData = false;

    protected $queryString = ['search', 'filterPenagih'];

    public function updatingSearch() { $this->resetPage(); }

    public function openImportModal() { $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }

    public function import(CollectionImportService $importService)
    {
        $this->validate(['file' => 'required|mimes:xlsx,xls,csv|max:51200']);
        
        try {
            if ($this->resetData) {
                Collection::truncate();
            }

            $importService->handle($this->file->getRealPath());
            
            $this->isImportOpen = false;
            $this->file = null;
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Collection berhasil diimport']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function deleteByPeriod()
    {
        if (!$this->deleteStartDate || !$this->deleteEndDate) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Pilih periode tanggal!']);
            return;
        }

        Collection::whereBetween('tanggal', [$this->deleteStartDate, $this->deleteEndDate])->delete();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data periode berhasil dihapus']);
    }

    public function delete($id)
    {
        Collection::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data berhasil dihapus']);
    }

    public function resetFilter()
    {
        $this->reset(['search', 'filterCabang', 'filterPenagih']);
    }

    public function render()
    {
        $query = Collection::query();

        // Fitur Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('receive_no', 'like', '%'.$this->search.'%')
                  ->orWhere('outlet_name', 'like', '%'.$this->search.'%')
                  ->orWhere('invoice_no', 'like', '%'.$this->search.'%');
            });
        }

        // Fitur Filter
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        if ($this->filterPenagih) {
            $query->where('penagih', $this->filterPenagih);
        }

        $collections = $query->latest('tanggal')->paginate(15);

        // Summary Data
        $summary = [
            'total_cair' => (clone $query)->sum('receive_amount'),
            'total_bukti' => (clone $query)->distinct('receive_no')->count(),
            'total_faktur' => (clone $query)->distinct('invoice_no')->count(),
        ];

        // Opsi Dropdown
        $optCabang = Collection::distinct()->pluck('cabang')->filter()->toArray();
        $optPenagih = Collection::distinct()->whereNotNull('penagih')->pluck('penagih')->toArray();

        return view('livewire.transaksi.collection-index', [
            'collections' => $collections,
            'summary' => $summary,
            'optCabang' => $optCabang,
            'optPenagih' => $optPenagih
        ])->layout('layouts.app');
    }
}