<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Transaksi\Penjualan;
use App\Services\Import\PenjualanImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PenjualanIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $startDate;
    public $endDate;
    
    // --- PASTIKAN INI ARRAY ---
    public $filterCabang = []; 
    // -------------------------

    // Modal Import
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // Modal Hapus Tanggal
    public $isDeleteDateOpen = false;
    public $deleteDateInput;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    
    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Penjualan::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
        }

        // --- FILTER MULTI SELECT ---
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }
        // ---------------------------

        $summary = [
            'total_omzet'  => (clone $query)->sum('total_grand'),
            'total_faktur' => (clone $query)->distinct('trans_no')->count('trans_no'),
            'total_items'  => (clone $query)->count(),
        ];

        $penjualans = $query->orderBy('tgl_penjualan', 'desc')->paginate(50);

        $optCabang = Cache::remember('opt_cabang_jual', 3600, fn() => 
            Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang')
        );

        return view('livewire.transaksi.penjualan-index', compact('penjualans', 'optCabang', 'summary'))
            ->layout('layouts.app', ['header' => 'Transaksi Penjualan']);
    }

    // --- IMPORT ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() 
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);
        
        $lock = Cache::lock('importing_penjualan', 600);
        if (!$lock->get()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Import sedang berjalan. Tunggu!']);
            return;
        }

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            $path = $this->file->store('temp-import', 'local');
            $fullPath = Storage::disk('local')->path($path);

            $stats = (new PenjualanImportService)->handle($fullPath, $this->resetData); 
            
            if(Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            Cache::forget('opt_cabang_jual');
            $this->closeImportModal();
            
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sukses! " . number_format($stats['processed']) . " Data masuk."]);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        } finally {
            $lock->release();
        }
    }

    // --- DELETE PER TANGGAL ---
    public function openDeleteDateModal() { $this->resetErrorBag(); $this->isDeleteDateOpen = true; }
    public function closeDeleteDateModal() { $this->isDeleteDateOpen = false; $this->deleteDateInput = null; }

    public function deleteByDate()
    {
        $this->validate(['deleteDateInput' => 'required|date']);
        $date = $this->deleteDateInput;
        
        $count = Penjualan::whereDate('tgl_penjualan', $date)->count();
        if ($count == 0) {
            $this->addError('deleteDateInput', 'Tidak ada data pada tanggal ini.');
            return;
        }

        Penjualan::whereDate('tgl_penjualan', $date)->delete();
        $this->closeDeleteDateModal();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => "$count Data tanggal $date dihapus."]);
        Cache::forget('opt_cabang_jual');
    }

    public function delete($id) {
        Penjualan::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data berhasil dihapus']);
    }
    public $deleteStartDate, $deleteEndDate;
    public function deleteByPeriod()
{
    $this->validate([
        'deleteStartDate' => 'required|date',
        'deleteEndDate' => 'required|date|after_or_equal:deleteStartDate',
    ]);

    try {
        $query = \App\Models\Transaksi\Penjualan::whereBetween('tgl_penjualan', [$this->deleteStartDate, $this->deleteEndDate]);
        $count = $query->count();

        if ($count > 0) {
            $query->delete();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "$count data Penjualan berhasil dihapus."]);
        } else {
            $this->dispatch('show-toast', ['type' => 'warning', 'message' => "Data tidak ditemukan."]);
        }
        $this->reset(['deleteStartDate', 'deleteEndDate']);
    } catch (\Exception $e) {
        $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
    }
}
}