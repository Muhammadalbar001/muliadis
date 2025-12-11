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
        // 1. Validasi
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        // --- TAMBAHAN PENTING: JEBOL LIMIT PHP ---
        set_time_limit(0);              // Unlimited execution time
        ini_set('memory_limit', '1024M'); // Naikkan memory ke 1GB sementara
        // ------------------------------------------

        try {
            // 2. Simpan file
            $path = $this->file->store('temp-import', 'local');
            
            // 3. Ambil Path Windows yang Benar
            $fullPath = Storage::disk('local')->path($path);

            // 4. Proses Import
            (new PenjualanImportService)->handle($fullPath, $this->resetData); 
            
            // 5. Hapus file temp
            if(Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            // 6. Reset UI
            Cache::forget('opt_cabang_jual');
            $this->closeImportModal();
            
            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => 'Import Selesai! Data berhasil masuk.'
            ]);

        } catch (\Exception $e) {
            // ... (kode catch tetap sama) ...
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function delete($id) {
        Penjualan::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Penjualan berhasil dihapus']);
    }
}