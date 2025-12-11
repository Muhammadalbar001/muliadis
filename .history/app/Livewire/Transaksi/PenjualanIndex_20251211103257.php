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
    
    // UPDATE: Ubah jadi array untuk Multi-Select
    public $filterCabang = []; 

    // MODAL IMPORT
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // Reset halaman saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    
    // BARU: Fungsi Reset Filter
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
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
        }

        // UPDATE: Gunakan whereIn untuk array
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        $penjualans = $query->orderBy('tgl_penjualan', 'desc')->paginate(10);

        // Ambil Opsi Cabang (Cached)
        $optCabang = Cache::remember('opt_cabang_jual', 60, fn() => 
            Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang')
        );

        return view('livewire.transaksi.penjualan-index', compact('penjualans', 'optCabang'))
            ->layout('layouts.app', ['header' => 'Transaksi Penjualan']);
    }

    // ... (Fungsi Import & Delete biarkan tetap sama seperti sebelumnya) ...
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    // Jangan lupa import Facade ini di paling atas file:
    // use Illuminate\Support\Facades\Storage;

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:153600' // Max 150MB
        ]);

        // SETUP PHP AGAR TIDAK TIMEOUT (Penting untuk file besar)
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            // 1. Simpan file ke folder khusus 'temp-import' di disk 'local'
            $filename = $this->file->store('temp-import', 'local');
            
            // 2. AMBIL FULL PATH YANG BENAR (Solusi Error Windows)
            // Fungsi path() ini akan otomatis mengubah '/' menjadi '\' di Windows
            $fullPath = Storage::disk('local')->path($filename);

            // 3. Proses Import dengan Service
            (new PenjualanImportService)->handle($fullPath, $this->resetData); 
            
            // 4. Hapus file temp setelah selesai
            if(Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }

            // 5. Reset UI
            Cache::forget('opt_cabang_jual');
            $this->closeImportModal();
            
            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => 'Data Penjualan berhasil diimport!'
            ]);

        } catch (\Exception $e) {
            // Hapus file jika error di tengah jalan
            if(isset($filename) && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }

            $this->dispatch('show-toast', [
                'type' => 'error', 
                'message' => 'Gagal Import: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id) {
        Penjualan::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Data dihapus']);
    }
}