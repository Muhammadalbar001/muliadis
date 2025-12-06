<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Produk;
use App\Services\Import\ProdukImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProdukIndex extends Component
{
    use WithFileUploads, WithPagination;

    // Properties Tampilan
    public $search = '';
    public $isOpen = false;
    public $iteration = 1; // Untuk reset file input

    // Properties Form Input
    public $productId;
    public $kode_item;   // Mapping ke 'sku'
    public $nama_item;   // Mapping ke 'name_item'
    public $satuan_jual; // Mapping ke 'oum'
    public $harga_jual;  // Mapping ke 'buy' (sementara, karena kolom harga_jual tidak ada di DB)
    
    // Properties Import
    public $file;

    // Reset pagination saat search berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // PERBAIKAN DI SINI: Gunakan 'name_item' dan 'sku' sesuai database
        $produks = Produk::query()
            ->where('name_item', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%') 
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.master.produk-index', [
            'produks' => $produks
        ])->layout('layouts.app', ['header' => 'Manajemen Produk']);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->productId = null;
        $this->kode_item = '';
        $this->nama_item = '';
        $this->satuan_jual = '';
        $this->harga_jual = '';
    }

    public function store()
    {
        // Validasi
        $this->validate([
            'kode_item' => 'required|unique:produks,sku,' . $this->productId, // Cek unique ke kolom 'sku'
            'nama_item' => 'required',
        ]);

        // Simpan ke Database (Mapping Input -> Kolom DB)
        Produk::updateOrCreate(['id' => $this->productId], [
            'sku'       => $this->kode_item,
            'name_item' => $this->nama_item,
            'oum'       => $this->satuan_jual,
            'stok'      => '0', // Default stok string
            
            // Catatan: Di migrasi Anda TIDAK ADA kolom 'harga_jual' atau 'price'.
            // Sementara saya simpan ke kolom 'buy' agar tidak error.
            // Sebaiknya Anda buat migration baru untuk menambah kolom 'selling_price'.
            'buy'       => $this->harga_jual ?? 0, 
        ]);

        session()->flash('success', $this->productId ? 'Produk berhasil diperbarui.' : 'Produk berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $this->productId = $id;
        
        // Ambil data dari DB ke Form
        $this->kode_item   = $produk->sku;       // DB: sku
        $this->nama_item   = $produk->name_item; // DB: name_item
        $this->satuan_jual = $produk->oum;       // DB: oum
        $this->harga_jual  = $produk->buy;       // DB: buy (sementara)

        $this->openModal();
    }

    public function delete($id)
    {
        try {
            Produk::find($id)->delete();
            session()->flash('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:102400',
        ]);

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            $importService = new ProdukImportService();
            $count = $importService->handle($fullPath);

            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            $this->file = null;
            $this->iteration++;
            
            session()->flash('success', "BERHASIL! $count produk telah diimport.");

        } catch (\Exception $e) {
            $this->file = null;
            $this->iteration++;
            Log::error('Import Gagal: ' . $e->getMessage());
            session()->flash('error', 'GAGAL: ' . $e->getMessage());
        }
    }
}