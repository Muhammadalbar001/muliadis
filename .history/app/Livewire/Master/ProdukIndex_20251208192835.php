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
        $this->validate(['file' => 'required|file|mimes:xlsx,xls|max:10240']);
        
        try {
            $path = $this->file->store('temp-import');
            $fullPath = storage_path('app/' . $path);
            
            // Panggil Service Import Anda
            (new ProdukImportService)->handle($fullPath);
            
            // Hapus File Temp
            unlink($fullPath);
            
            // Hapus Cache agar opsi filter terupdate dengan data baru
            Cache::forget('opt_prod_cabang');
            Cache::forget('opt_prod_kategori');
            Cache::forget('opt_prod_divisi');
            Cache::forget('opt_prod_supplier');

            $this->closeImportModal();
            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => 'Data Produk berhasil diimport!']);
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }

    // --- 4. DELETE LOGIC ---
    public function delete($id)
    {
        Produk::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Produk berhasil dihapus.']);
    }
}