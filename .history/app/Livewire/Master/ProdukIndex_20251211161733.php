<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Master\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Services\Import\ProdukImportService;

class ProdukIndex extends Component
{
    use WithPagination, WithFileUploads;

    // --- 1. FILTER PROPERTIES (UBAH JADI ARRAY) ---
    public $search = '';
    public $filterCabang = [];   // Array
    public $filterKategori = []; // Array
    public $filterDivisi = [];   // Array
    public $filterSupplier = []; // Array
    public $filterStok = '';     // Tetap string (opsi tunggal)

    // --- 2. MODAL & UPLOAD ---
    public $isImportOpen = false;
    public $file;

    // Reset Page saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterKategori() { $this->resetPage(); }
    public function updatedFilterSupplier() { $this->resetPage(); }
    public function updatedFilterDivisi() { $this->resetPage(); }
    public function updatedFilterStok() { $this->resetPage(); }

    public function resetFilter()
    {
        // Reset menjadi array kosong
        $this->filterCabang = [];
        $this->filterKategori = [];
        $this->filterDivisi = [];
        $this->filterSupplier = [];
        $this->filterStok = '';
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Produk::query();

        // A. SEARCH
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name_item', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('ccode', 'like', '%' . $this->search . '%');
            });
        }

        // B. FILTER MULTI-SELECT (Gunakan whereIn jika array tidak kosong)
        $query->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang));
        $query->when(!empty($this->filterKategori), fn($q) => $q->whereIn('kategori', $this->filterKategori));
        $query->when(!empty($this->filterDivisi), fn($q) => $q->whereIn('divisi', $this->filterDivisi));
        $query->when(!empty($this->filterSupplier), fn($q) => $q->whereIn('supplier', $this->filterSupplier));

        // C. FILTER STOK
        if ($this->filterStok === 'ready') {
            $query->where('stok', '>', 0);
        } elseif ($this->filterStok === 'empty') {
            $query->where(function($q) {
                $q->where('stok', '=', 0)->orWhereNull('stok');
            });
        }

        $produks = $query->orderBy('created_at', 'desc')->paginate(20);

        // DATA OPSI (Cached)
        $optCabang = Cache::remember('opt_prod_cabang', 3600, fn() => Produk::select('cabang')->distinct()->whereNotNull('cabang')->orderBy('cabang')->pluck('cabang'));
        $optKategori = Cache::remember('opt_prod_kategori', 3600, fn() => Produk::select('kategori')->distinct()->whereNotNull('kategori')->orderBy('kategori')->pluck('kategori'));
        $optDivisi = Cache::remember('opt_prod_divisi', 3600, fn() => Produk::select('divisi')->distinct()->whereNotNull('divisi')->orderBy('divisi')->pluck('divisi'));
        $optSupplier = Cache::remember('opt_prod_supplier', 3600, fn() => Produk::select('supplier')->distinct()->whereNotNull('supplier')->orderBy('supplier')->pluck('supplier'));

        return view('livewire.master.produk-index', compact(
            'produks', 'optCabang', 'optKategori', 'optDivisi', 'optSupplier'
        ))->layout('layouts.app', ['header' => 'Master Produk']);
    }

    // --- IMPORT & DELETE (Sama seperti sebelumnya) ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }

    public function import()
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:10240']);
        try {
            $path = $this->file->store('temp-import', 'local');
            $fullPath = Storage::disk('local')->path($path);
            (new ProdukImportService)->handle($fullPath);
            
            if(Storage::disk('local')->exists($path)) Storage::disk('local')->delete($path);
            
            Cache::forget('opt_prod_cabang');
            Cache::forget('opt_prod_kategori');
            Cache::forget('opt_prod_divisi');
            Cache::forget('opt_prod_supplier');

            $this->closeImportModal();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Produk berhasil diimport!']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Import: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        Produk::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Produk berhasil dihapus.']);
    }
}