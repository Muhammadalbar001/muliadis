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

    // --- Properties Tampilan ---
    public $search = '';
    public $isInputOpen = false;  // Modal untuk Input Manual/Edit
    public $isImportOpen = false; // Modal Khusus Import Excel (BARU)
    
    // --- Properties Form Manual (Untuk Edit Data Salah) ---
    public $productId;
    public $kode_item;   
    public $nama_item;   
    public $satuan_jual; 
    public $harga_jual;  
    
    // --- Properties Import Excel ---
    public $file;
    public $iteration = 1; // Trik untuk reset input file

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query disesuaikan dengan kolom database Anda: 'sku', 'name_item', 'stok'
        $produks = Produk::query()
            ->where('name_item', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%') 
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.master.produk-index', [
            'produks' => $produks
        ])->layout('layouts.app', ['header' => 'Master Produk (Import Center)']);
    }

    // ==========================================
    // BAGIAN 1: LOGIKA IMPORT (UTAMA)
    // ==========================================

    public function openImportModal()
    {
        $this->isImportOpen = true;
        $this->resetErrorBag();
    }

    public function closeImportModal()
    {
        $this->isImportOpen = false;
        $this->file = null;
        $this->iteration++; // Reset file input di view
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:102400', // Max 100MB
        ]);

        $path = null;

        try {
            // 1. Simpan file sementara
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) {
                throw new \Exception("File gagal disimpan di server.");
            }

            // 2. Panggil Service Import (Yang sudah Anda miliki)
            $importService = new ProdukImportService();
            $count = $importService->handle($fullPath);

            // 3. Hapus file temporary
            if (Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            // 4. Tutup Modal & Beri Pesan Sukses
            $this->closeImportModal();
            session()->flash('success', "SUKSES! $count data produk berhasil direkap/diimport.");

        } catch (\Exception $e) {
            // Log error
            Log::error('Import Gagal: ' . $e->getMessage());
            
            // Hapus file jika error di tengah jalan
            if (isset($filename) && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }

            // Tampilkan error ke user
            $this->addError('file', 'Terjadi Kesalahan: ' . $e->getMessage());
        }
    }

    // ==========================================
    // BAGIAN 2: LOGIKA MANUAL (PELENGKAP/EDIT)
    // ==========================================

    public function create()
    {
        $this->resetInputFields();
        $this->isInputOpen = true;
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $this->productId = $id;
        
        // Mapping Kolom Database -> Form
        $this->kode_item   = $produk->sku;
        $this->nama_item   = $produk->name_item;
        $this->satuan_jual = $produk->oum;
        $this->harga_jual  = $produk->buy;

        $this->isInputOpen = true;
    }

    public function store()
    {
        $this->validate([
            'kode_item' => 'required|unique:produks,sku,' . $this->productId,
            'nama_item' => 'required',
        ]);

        Produk::updateOrCreate(['id' => $this->productId], [
            'sku'       => $this->kode_item,
            'name_item' => $this->nama_item,
            'oum'       => $this->satuan_jual,
            'buy'       => $this->harga_jual ?? 0, 
            'stok'      => '0', // Default
        ]);

        session()->flash('success', 'Data produk berhasil disimpan.');
        $this->closeInputModal();
    }

    public function delete($id)
    {
        try {
            Produk::find($id)->delete();
            session()->flash('success', 'Produk dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    public function closeInputModal()
    {
        $this->isInputOpen = false;
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
}