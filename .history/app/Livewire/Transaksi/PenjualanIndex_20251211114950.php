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
            'file' => 'required|file|mimes:xlsx,xls,csv|max:153600'
        ]);

        // CEK LOCK: Mencegah proses jalan 2x (Solusi Data Ganda)
        // Kita kunci proses import selama 10 menit
        $lock = Cache::lock('importing_penjualan', 600);

        if (!$lock->get()) {
            $this->dispatch('show-toast', [
                'type' => 'error', 
                'message' => 'Sedang ada proses import berjalan. Mohon tunggu!'
            ]);
            return;
        }

        // Setup PHP Unlimited (Wajib untuk 160rb baris)
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            $path = $this->file->store('temp-import', 'local');
            $fullPath = Storage::disk('local')->path($path);

            // 1. Panggil Service
            // Service Anda mengembalikan ARRAY ['processed' => 100, ...], bukan Integer
            $stats = (new PenjualanImportService)->handle($fullPath, $this->resetData); 
            
            // 2. Ambil Jumlah Data yang Benar
            $count = $stats['processed'] ?? 0; 
            $skipped = $stats['skipped_empty'] ?? 0;

            if(Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            Cache::forget('opt_cabang_jual');
            $this->closeImportModal();
            
            // 3. Tampilkan Notifikasi dengan Angka
            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => "BERHASIL! Masuk: " . number_format($count) . " baris. (Skip: $skipped)"
            ]);

        } catch (\Exception $e) {
            if(isset($path) && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        } finally {
            // Selalu lepas kunci setelah selesai (atau error)
            $lock->release();
        }
    }

    public function delete($id) {
        Penjualan::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Data dihapus']);
    }
}