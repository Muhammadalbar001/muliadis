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
    
    // Multi-Select Filter (Array)
    public $filterCabang = []; 

    // MODAL IMPORT
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // Reset Page saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedStartDate() { $this->resetPage(); }
    public function updatedEndDate() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    
    // Fungsi Reset Filter Total
    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate']);
        $this->filterCabang = []; // Reset array manual karena reset() kadang bug di array
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
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%'); // Tambah cari sales
            });
        }

        // 2. Filter Tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
        }

        // 3. Filter Cabang (Multi-Select)
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        $penjualans = $query->orderBy('tgl_penjualan', 'desc')->paginate(10);

        // Ambil Opsi Cabang (Cached 1 Jam)
        $optCabang = Cache::remember('opt_cabang_jual', 3600, fn() => 
            Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->orderBy('cabang')->pluck('cabang')
        );

        return view('livewire.transaksi.penjualan-index', compact('penjualans', 'optCabang'))
            ->layout('layouts.app', ['header' => 'Transaksi Penjualan']);
    }

    // --- IMPORT LOGIC ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() 
    {
        // 1. Validasi File
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            // 2. Simpan file ke disk 'local' (folder storage/app/temp-import)
            $path = $this->file->store('temp-import', 'local');
            
            // 3. Ambil Full Path yang valid untuk Windows (C:\laragon\www\...)
            // Ini kuncinya: function path() akan menormalkan garis miring
            $fullPath = Storage::disk('local')->path($path);

            // 4. Panggil Service Import
            (new PenjualanImportService)->handle($fullPath, $this->resetData); 
            
            // 5. Hapus file temp setelah sukses
            if(Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            // 6. Reset Cache & UI
            Cache::forget('opt_cabang_jual'); // Hapus cache filter cabang
            $this->closeImportModal();
            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => 'Data Penjualan berhasil diimport!'
            ]);

        } catch (\Exception $e) {
            // Cleanup: Hapus file jika terjadi error
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
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Penjualan berhasil dihapus']);
    }
}