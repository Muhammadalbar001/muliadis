<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Master\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Services\Import\ProdukImportService; // Pastikan ini ada jika pakai Service

class ProdukIndex extends Component
{
    use WithPagination, WithFileUploads;

    // --- 1. FILTER PROPERTIES ---
    public $search = '';
    public $filterCabang = '';
    public $filterKategori = '';
    public $filterDivisi = '';
    public $filterSupplier = '';
    public $filterStok = ''; // Opsi: '', 'ready', 'empty'

    // --- 2. MODAL & UPLOAD PROPERTIES ---
    public $isImportOpen = false;
    public $file;

    // Reset halaman ke 1 saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterKategori() { $this->resetPage(); }
    public function updatedFilterSupplier() { $this->resetPage(); }
    public function updatedFilterDivisi() { $this->resetPage(); }
    public function updatedFilterStok() { $this->resetPage(); }

    // Tombol Reset Semua Filter
    public function resetFilter()
    {
        $this->reset(['filterCabang', 'filterKategori', 'filterDivisi', 'filterSupplier', 'filterStok', 'search']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Produk::query();

        // A. SEARCH (Cari Kode, Nama, Barcode)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name_item', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('ccode', 'like', '%' . $this->search . '%');
            });
        }

        // B. FILTER DINAMIS
        if ($this->filterCabang) $query->where('cabang', $this->filterCabang);
        if ($this->filterKategori) $query->where('kategori', $this->filterKategori);
        if ($this->filterDivisi) $query->where('divisi', $this->filterDivisi);
        if ($this->filterSupplier) $query->where('supplier', $this->filterSupplier);

        // C. FILTER STOK (Canggih)
        if ($this->filterStok === 'ready') {
            $query->where('stok', '>', 0);
        } elseif ($this->filterStok === 'empty') {
            $query->where(function($q) {
                $q->where('stok', '=', 0)
                  ->orWhere('stok', '=', '0')
                  ->orWhereNull('stok');
            });
        }

        // Ambil Data (Pagination)
        $produks = $query->orderBy('created_at', 'desc')->paginate(10);

        // D. DATA OPSI UNTUK DROPDOWN (DICACHE 1 JAM BIAR CEPAT)
        // Kita pakai Cache::remember agar tidak query berulang-ulang setiap ketik search
        $optCabang = Cache::remember('opt_prod_cabang', 3600, fn() => Produk::select('cabang')->distinct()->whereNotNull('cabang')->orderBy('cabang')->pluck('cabang'));
        $optKategori = Cache::remember('opt_prod_kategori', 3600, fn() => Produk::select('kategori')->distinct()->whereNotNull('kategori')->orderBy('kategori')->pluck('kategori'));
        $optDivisi = Cache::remember('opt_prod_divisi', 3600, fn() => Produk::select('divisi')->distinct()->whereNotNull('divisi')->orderBy('divisi')->pluck('divisi'));
        $optSupplier = Cache::remember('opt_prod_supplier', 3600, fn() => Produk::select('supplier')->distinct()->whereNotNull('supplier')->orderBy('supplier')->pluck('supplier'));

        return view('livewire.master.produk-index', compact(
            'produks', 
            'optCabang', 'optKategori', 'optDivisi', 'optSupplier'
        ))->layout('layouts.app', ['header' => 'Master Produk']);
    }

    // --- 3. IMPORT LOGIC (Sesuai kode Anda sebelumnya) ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }

    public function import()
    {
        // Validasi
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);
        
        try {
            // 1. Simpan file ke storage (disk 'local')
            $path = $this->file->store('temp-import', 'local');
            
            // 2. Ambil Absolute Path yang Valid untuk Windows (Gunakan Storage facade)
            // Ini akan otomatis menghasilkan C:\laragon\www\...\storage\app\temp-import\file.xlsx
            $fullPath = Storage::disk('local')->path($path);
            
            // 3. Panggil Service Import
            (new ProdukImportService)->handle($fullPath);
            
            // 4. Hapus File Temp (Gunakan Storage facade juga agar aman)
            if(Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
            
            // 5. Clear Cache & Reset UI
            Cache::forget('opt_prod_cabang');
            Cache::forget('opt_prod_kategori');
            Cache::forget('opt_prod_divisi');
            Cache::forget('opt_prod_supplier');

            $this->closeImportModal();
            
            // Kirim notifikasi sukses
            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => 'Data Produk berhasil diimport!'
            ]);
            
        } catch (\Exception $e) {
            // Jika error, hapus file jika sempat terupload
            if(isset($path) && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            $this->dispatch('show-toast', [
                'type' => 'error', 
                'message' => 'Gagal Import: ' . $e->getMessage()
            ]);
        }
    }

    // --- 4. DELETE LOGIC ---
    public function delete($id)
    {
        Produk::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Produk berhasil dihapus.']);
    }
}