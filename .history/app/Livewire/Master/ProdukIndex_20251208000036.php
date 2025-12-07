<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Produk;
use App\Services\Import\ProdukImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProdukIndex extends Component
{
    use WithFileUploads, WithPagination;

    // --- 1. PROPERTIES ---
    public $search = '';
    
    // Filter Properties (Ubah jadi Array untuk Multi-Select)
    public $filterCabang = [];
    public $filterKategori = [];
    public $filterDivisi = [];
    public $filterSupplier = [];
    public $filterStok = ''; // Stok tetap single select (Mode: Ready/Empty/All)

    // Modal
    public $isInputOpen = false;
    public $isImportOpen = false;
    
    // CRUD
    public $productId;
    public $kode_item;   
    public $nama_item;   
    public $satuan_jual; 
    public $harga_jual;  
    
    // Import
    public $file;
    public $iteration = 1;

    // Reset Pagination
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterKategori() { $this->resetPage(); }
    public function updatedFilterDivisi() { $this->resetPage(); }
    public function updatedFilterSupplier() { $this->resetPage(); }
    public function updatedFilterStok() { $this->resetPage(); }

    // Reset Filter Button Logic
    public function resetFilter()
    {
        $this->filterCabang = [];
        $this->filterKategori = [];
        $this->filterDivisi = [];
        $this->filterSupplier = [];
        $this->filterStok = '';
        $this->resetPage();
    }

    // --- 2. RENDER ---
    public function render()
    {
        $query = Produk::query();

        // A. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name_item', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('ccode', 'like', '%' . $this->search . '%');
            });
        }

        // B. Filter Multi-Select (Pakai whereIn jika array tidak kosong)
        $query->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
              ->when(!empty($this->filterKategori), fn($q) => $q->whereIn('kategori', $this->filterKategori))
              ->when(!empty($this->filterDivisi), fn($q) => $q->whereIn('divisi', $this->filterDivisi))
              ->when(!empty($this->filterSupplier), fn($q) => $q->whereIn('supplier', $this->filterSupplier));

        // C. Filter Stok
        if ($this->filterStok === 'ready') {
            $query->where(function($q) {
                $q->where('stok', '!=', '0')->where('stok', '!=', '');
            });
        } elseif ($this->filterStok === 'empty') {
            $query->where(function($q) {
                $q->where('stok', '0')->orWhere('stok', '')->orWhereNull('stok');
            });
        }

        $produks = $query->orderBy('created_at', 'desc')->paginate(10);

        // D. Options (Cache 1 Jam)
        $optCabang   = Cache::remember('opt_cabang', 3600, fn() => Produk::select('cabang')->distinct()->whereNotNull('cabang')->where('cabang', '!=', '')->orderBy('cabang')->pluck('cabang'));
        $optKategori = Cache::remember('opt_kategori', 3600, fn() => Produk::select('kategori')->distinct()->whereNotNull('kategori')->where('kategori', '!=', '')->orderBy('kategori')->pluck('kategori'));
        $optDivisi   = Cache::remember('opt_divisi', 3600, fn() => Produk::select('divisi')->distinct()->whereNotNull('divisi')->where('divisi', '!=', '')->orderBy('divisi')->pluck('divisi'));
        $optSupplier = Cache::remember('opt_supplier', 3600, fn() => Produk::select('supplier')->distinct()->whereNotNull('supplier')->where('supplier', '!=', '')->orderBy('supplier')->pluck('supplier'));

        return view('livewire.master.produk-index', [
            'produks'     => $produks,
            'optCabang'   => $optCabang,
            'optKategori' => $optKategori,
            'optDivisi'   => $optDivisi,
            'optSupplier' => $optSupplier,
        ])->layout('layouts.app', ['header' => 'Master Produk']);
    }

    // ... (Bagian Import & CRUD Manual TETAP SAMA seperti kode sebelumnya) ...
    // Saya persingkat bagian bawah ini agar fokus ke perubahan filter, 
    // tapi pastikan Anda menyalin method import(), create(), store() dari jawaban sebelumnya.

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

            // Clear Cache Filter
            Cache::forget('opt_cabang'); Cache::forget('opt_kategori'); 
            Cache::forget('opt_divisi'); Cache::forget('opt_supplier');

            // LAPORAN AUDIT
            $msg = "Import Produk Selesai!\n" .
                "📊 Total Baris: " . number_format($stats['total_rows']) . "\n" .
                "✅ Masuk Database: " . number_format($stats['processed']) . "\n" .
                "♻️ Duplikat (Di File): " . number_format($stats['duplicates_found']);

            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => $msg]);
        } catch (\Exception $e) {
            if ($filename) Storage::disk('local')->delete($filename);
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }

    public function create() { $this->resetInputFields(); $this->isInputOpen = true; }
    public function edit($id) { $p = Produk::findOrFail($id); $this->productId=$id; $this->kode_item=$p->sku; $this->nama_item=$p->name_item; $this->satuan_jual=$p->oum; $this->harga_jual=$p->buy; $this->isInputOpen=true; }
    public function store() {
        $this->validate(['kode_item'=>'required|unique:produks,sku,'.$this->productId, 'nama_item'=>'required']);
        Produk::updateOrCreate(['id'=>$this->productId], ['sku'=>$this->kode_item, 'name_item'=>$this->nama_item, 'oum'=>$this->satuan_jual, 'buy'=>$this->harga_jual??0, 'stok'=>'0']);
        $this->dispatch('show-toast', ['type'=>'success', 'title'=>'Berhasil', 'message'=>'Data disimpan.']); $this->closeInputModal();
    }
    public function delete($id) { Produk::destroy($id); $this->dispatch('show-toast', ['type'=>'success', 'title'=>'Dihapus', 'message'=>'Data dihapus.']); }
    public function closeInputModal() { $this->isInputOpen = false; $this->resetInputFields(); }
    private function resetInputFields() { $this->productId=null; $this->kode_item=''; $this->nama_item=''; $this->satuan_jual=''; $this->harga_jual=''; }
}