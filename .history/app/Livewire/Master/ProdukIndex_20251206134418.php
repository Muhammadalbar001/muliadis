<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Produk;
use App\Services\Import\ProdukImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // Tambahkan Cache untuk performa filter

class ProdukIndex extends Component
{
    use WithFileUploads, WithPagination;

    // --- Properties Tampilan & Filter ---
    public $search = '';
    
    // Filter Properties
    public $filterCabang = '';
    public $filterKategori = '';
    public $filterDivisi = '';
    public $filterSupplier = '';
    public $filterStok = ''; // 'ready' atau 'empty'

    // Modal Properties
    public $isInputOpen = false;
    public $isImportOpen = false;
    
    // Form Manual
    public $productId;
    public $kode_item;   
    public $nama_item;   
    public $satuan_jual; 
    public $harga_jual;  
    
    // Import
    public $file;
    public $iteration = 1;

    // Reset pagination saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterKategori() { $this->resetPage(); }
    public function updatedFilterDivisi() { $this->resetPage(); }
    public function updatedFilterSupplier() { $this->resetPage(); }
    public function updatedFilterStok() { $this->resetPage(); }

    public function render()
    {
        // 1. Query Utama dengan Filter
        $query = Produk::query();

        // Search Text
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name_item', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('ccode', 'like', '%' . $this->search . '%');
            });
        }

        // Apply Dropdown Filters
        $query->when($this->filterCabang, fn($q) => $q->where('cabang', $this->filterCabang))
              ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
              ->when($this->filterDivisi, fn($q) => $q->where('divisi', $this->filterDivisi))
              ->when($this->filterSupplier, fn($q) => $q->where('supplier', $this->filterSupplier));

        // Filter Stok (Logic Khusus karena kolom stok string di DB)
        if ($this->filterStok === 'ready') {
            $query->where('stok', '!=', '0')->where('stok', '!=', '');
        } elseif ($this->filterStok === 'empty') {
            $query->where(function($q) {
                $q->where('stok', '0')->orWhere('stok', '')->orWhereNull('stok');
            });
        }

        $produks = $query->orderBy('created_at', 'desc')->paginate(10);

        // 2. Ambil Data Opsi Filter (Di-cache 60 menit agar tidak berat)
        // Kita ambil list unik untuk dropdown
        $optCabang = Cache::remember('opt_cabang', 3600, fn() => Produk::select('cabang')->distinct()->whereNotNull('cabang')->where('cabang', '!=', '')->orderBy('cabang')->pluck('cabang'));
        $optKategori = Cache::remember('opt_kategori', 3600, fn() => Produk::select('kategori')->distinct()->whereNotNull('kategori')->where('kategori', '!=', '')->orderBy('kategori')->pluck('kategori'));
        $optDivisi = Cache::remember('opt_divisi', 3600, fn() => Produk::select('divisi')->distinct()->whereNotNull('divisi')->where('divisi', '!=', '')->orderBy('divisi')->pluck('divisi'));
        // Supplier mungkin banyak, jadi ambil yg ada saja
        $optSupplier = Cache::remember('opt_supplier', 3600, fn() => Produk::select('supplier')->distinct()->whereNotNull('supplier')->where('supplier', '!=', '')->orderBy('supplier')->pluck('supplier'));


        return view('livewire.master.produk-index', [
            'produks' => $produks,
            'optCabang' => $optCabang,
            'optKategori' => $optKategori,
            'optDivisi' => $optDivisi,
            'optSupplier' => $optSupplier,
        ])->layout('layouts.app', ['header' => 'Master Produk']);
    }

    // ==========================================
    // LOGIKA IMPORT (Sama seperti sebelumnya)
    // ==========================================
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; $this->iteration++; }

    public function import()
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,csv,xls|max:102400']);
        $filename = null;
        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) throw new \Exception("Gagal menyimpan file.");

            $importService = new ProdukImportService();
            $stats = $importService->handle($fullPath);

            if (Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            $this->closeImportModal();

            // Clear Cache Filter agar data baru muncul di dropdown
            Cache::forget('opt_cabang');
            Cache::forget('opt_kategori');
            Cache::forget('opt_divisi');
            Cache::forget('opt_supplier');

            // Notifikasi
            $uniqueCount = $stats['processed'] - $stats['duplicates_marked'];
            $msg = "Total Excel: " . number_format($stats['total_rows']) . "\n" .
                   "✅ Masuk: " . number_format($uniqueCount) . "\n" .
                   "♻️ Duplikat: " . number_format($stats['duplicates_marked']);

            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Import Sukses', 'message' => $msg]);

        } catch (\Exception $e) {
            if ($filename && Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            Log::error('Import Gagal: ' . $e->getMessage());
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }

    // ==========================================
    // LOGIKA MANUAL CRUD
    // ==========================================
    public function create() { $this->resetInputFields(); $this->isInputOpen = true; }
    public function edit($id) { 
        $produk = Produk::findOrFail($id); 
        $this->productId = $id; 
        $this->kode_item = $produk->sku;
        $this->nama_item = $produk->name_item;
        $this->satuan_jual = $produk->oum;
        $this->harga_jual = $produk->buy;
        $this->isInputOpen = true; 
    }
    public function store() {
        $this->validate(['kode_item' => 'required|unique:produks,sku,'.$this->productId, 'nama_item' => 'required']);
        Produk::updateOrCreate(['id' => $this->productId], [
            'sku' => $this->kode_item, 'name_item' => $this->nama_item, 
            'oum' => $this->satuan_jual, 'buy' => $this->harga_jual ?? 0, 'stok' => '0'
        ]);
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data disimpan.']);
        $this->closeInputModal();
    }
    public function delete($id) {
        try { Produk::find($id)->delete(); $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Produk dihapus.']); }
        catch (\Exception $e) { $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]); }
    }
    public function closeInputModal() { $this->isInputOpen = false; $this->resetInputFields(); }
    private function resetInputFields() { $this->productId = null; $this->kode_item = ''; $this->nama_item = ''; $this->satuan_jual = ''; $this->harga_jual = ''; }
}