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
    public $filterPenagih = '';

    // Properti Import
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // PROPERTI HAPUS PERIODE
    public $deleteStartDate;
    public $deleteEndDate;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterPenagih() { $this->resetPage(); }
    
    public function resetFilter() 
    { 
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'filterPenagih', 'deleteStartDate', 'deleteEndDate']); 
        $this->resetPage(); 
    }

    // FUNGSI HAPUS PERIODE (Berdasarkan Tgl Bayar)
    public function deleteByPeriod()
    {
        $this->validate([
            'deleteStartDate' => 'required|date',
            'deleteEndDate' => 'required|date|after_or_equal:deleteStartDate',
        ]);

        try {
            $query = Collection::whereBetween('tanggal', [$this->deleteStartDate, $this->deleteEndDate]);
            $count = $query->count();

            if ($count > 0) {
                $query->delete();
                $this->dispatch('show-toast', ['type' => 'success', 'message' => "$count data Pelunasan berhasil dihapus."]);
                Cache::forget('opt_coll_cabang');
                Cache::forget('opt_coll_penagih');
            } else {
                $this->dispatch('show-toast', ['type' => 'warning', 'message' => "Tidak ada data pada periode tersebut."]);
            }
            $this->reset(['deleteStartDate', 'deleteEndDate']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $query = Collection::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_bukti', 'like', '%'.$this->search.'%')
                  ->orWhere('receive_no', 'like', '%'.$this->search.'%')
                  ->orWhere('invoice_no', 'like', '%'.$this->search.'%')
                  ->orWhere('outlet_name', 'like', '%'.$this->search.'%');
            });
        }
        
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }
        
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }
        
        if ($this->filterPenagih) {
            $query->where('penagih', $this->filterPenagih);
        }

        $summary = [
            'total_cair'   => (clone $query)->sum('receive_amount'),
            'total_bukti'  => (clone $query)->distinct('receive_no')->count('receive_no'),
            'total_faktur' => (clone $query)->count(),
        ];

        $collections = $query->orderBy('tanggal', 'desc')->paginate(50);
        
        $optCabang = Cache::remember('opt_coll_cabang', 3600, fn() => 
            Collection::select('cabang')->distinct()->pluck('cabang')
        );
        
        $optPenagih = Cache::remember('opt_coll_penagih', 3600, fn() => 
            Collection::select('penagih')->distinct()->whereNotNull('penagih')->where('penagih', '!=', '')->pluck('penagih')
        );

        return view('livewire.transaksi.collection-index', compact('collections', 'optCabang', 'optPenagih', 'summary'))
            ->layout('layouts.app', ['header' => 'Collection']);
    }

    // Import Handlers
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);
        
        $path = $this->file->getRealPath(); // Lebih stabil untuk Windows/Laragon
        try {
            $stats = (new CollectionImportService)->handle($path, $this->resetData);
            
            Cache::forget('opt_coll_cabang');
            Cache::forget('opt_coll_penagih');
            
            $this->closeImportModal();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sukses import " . number_format($stats['processed']) . " data."]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id) { 
        Collection::destroy($id); 
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data dihapus']); 
    }
}