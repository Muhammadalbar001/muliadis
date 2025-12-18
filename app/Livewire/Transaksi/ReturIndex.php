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
    public $filterCabang = []; 

    // Properti Import
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // PROPERTI HAPUS PERIODE
    public $deleteStartDate;
    public $deleteEndDate;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    
    public function resetFilter() 
    { 
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'deleteStartDate', 'deleteEndDate']); 
        $this->resetPage(); 
    }

    // FUNGSI HAPUS PERIODE
    public function deleteByPeriod()
    {
        $this->validate([
            'deleteStartDate' => 'required|date',
            'deleteEndDate' => 'required|date|after_or_equal:deleteStartDate',
        ]);

        try {
            $query = Retur::whereBetween('tgl_retur', [$this->deleteStartDate, $this->deleteEndDate]);
            $count = $query->count();

            if ($count > 0) {
                $query->delete();
                $this->dispatch('show-toast', ['type' => 'success', 'message' => "$count data Retur berhasil dihapus."]);
                Cache::forget('opt_cabang_retur');
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
        $query = Retur::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%');
            });
        }
        
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_retur', [$this->startDate, $this->endDate]);
        }
        
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        $summary = [
            'total_nilai'  => (clone $query)->sum('total_grand'),
            'total_faktur' => (clone $query)->distinct('no_retur')->count('no_retur'),
            'total_items'  => (clone $query)->count(),
        ];

        $returs = $query->orderBy('tgl_retur', 'desc')->paginate(50);
        
        $optCabang = Cache::remember('opt_cabang_retur', 3600, fn() => 
            Retur::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang')
        );

        return view('livewire.transaksi.retur-index', compact('returs', 'optCabang', 'summary'))
            ->layout('layouts.app', ['header' => 'Retur Penjualan']);
    }

    // Import Handlers
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);
        
        $path = $this->file->getRealPath(); // Lebih aman untuk Laragon
        try {
            $stats = (new ReturImportService)->handle($path, $this->resetData);
            Cache::forget('opt_cabang_retur');
            $this->closeImportModal();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sukses import " . number_format($stats['processed']) . " data."]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id) { 
        Retur::destroy($id); 
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data dihapus']); 
    }
}