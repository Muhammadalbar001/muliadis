<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Transaksi\Penjualan;
use App\Services\Import\PenjualanImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage; // <--- WAJIB ADA INI

class PenjualanIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $startDate;
    public $endDate;
    
    // Filter Multi-Select
    public $filterCabang = []; 

    // MODAL IMPORT
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    
    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate']);
        $this->filterCabang = []; // Reset manual array
        $this->resetPage();
    }

    public function render()
    {
        $query = Penjualan::query();

        // 1. Pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%'); // Tambahkan cari sales juga
            });
        }

        // 2. Filter Tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
        }

        // 3. Filter Cabang (Array / Multi Select)
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        $penjualans = $query->orderBy('tgl_penjualan', 'desc')->paginate(10);

        // Cache Opsi Cabang
        $optCabang = Cache::remember('opt_cabang_jual', 3600, fn() => 
            Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->orderBy('cabang')->pluck('cabang')
        );

        return view('livewire.transaksi.penjualan-index', compact('penjualans', 'optCabang'))
            ->layout('layouts.app', ['header' => 'Transaksi Penjualan']);
    }

    // --- LOGIC MODAL ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    // --- PERBAIKAN LOGIC IMPORT (FIX PATH WINDOWS) ---
    public function import() 
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            // 1. Simpan file ke folder temp-import di storage lokal
            $path = $this->file->store('temp-import', 'local');
            
            // 2. Ambil Absolute Path yang Valid untuk Windows (Fix Slashes)
            $fullPath = Storage::disk('local')->path($path);

            // 3. Proses Import
            (new PenjualanImportService)->handle($fullPath, $this->resetData); 
            
            // 4. Hapus file temp setelah sukses agar storage tidak penuh
            if(Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            // 5. Reset Cache agar filter terupdate
            Cache::forget('opt_cabang_jual'); 

            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => 'Data Penjualan berhasil diimport!'
            ]);
            
            $this->closeImportModal();

        } catch (\Exception $e) {
            // Cleanup jika error
            if(isset($path) && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            $this->dispatch('show-toast', [
                'type' => 'error', 
                'message' => 'Gagal Import: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id) {
        Penjualan::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data penjualan dihapus']);
    }
}