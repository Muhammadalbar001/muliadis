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

    // --- Properties untuk Tampilan & Filter ---
    public $search = '';
    public $isOpen = false; // INI YANG MENYEBABKAN ERROR (Variable ini sebelumnya tidak ada)
    
    // --- Properties untuk Form Input (Data Binding) ---
    public $productId;
    public $kode_item;
    public $nama_item;
    public $satuan_jual;
    public $harga_jual;

    // --- Properties Import ---
    public $file;
    public $iteration = 1;

    // Reset pagination saat search berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Sesuaikan nama kolom dengan database Anda (misal: 'nama_item' atau 'name_item')
        // Disini saya gunakan 'nama_item' agar sesuai dengan View yang saya buat sebelumnya
        $produks = Produk::query()
            ->where('nama_item', 'like', '%' . $this->search . '%')
            ->orWhere('kode_item', 'like', '%' . $this->search . '%') // Asumsi kolom kode adalah kode_item
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.master.produk-index', [
            'produks' => $produks
        ])->layout('layouts.app', ['header' => 'Manajemen Produk']);
    }

    // --- LOGIKA MODAL & CRUD ---

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
        $this->validate([
            'kode_item' => 'required|unique:produks,kode_item,' . $this->productId,
            'nama_item' => 'required',
            'harga_jual' => 'numeric|nullable',
        ]);

        Produk::updateOrCreate(['id' => $this->productId], [
            'kode_item' => $this->kode_item,
            'nama_item' => $this->nama_item,
            'satuan_jual' => $this->satuan_jual,
            'harga_jual' => $this->harga_jual ?? 0,
            // Field lain bisa ditambahkan default value jika diperlukan
        ]);

        session()->flash('success', $this->productId ? 'Produk berhasil diperbarui.' : 'Produk berhasil ditambahkan.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $this->productId = $id;
        $this->kode_item = $produk->kode_item;
        $this->nama_item = $produk->nama_item; // Pastikan kolom di DB 'nama_item'
        $this->satuan_jual = $produk->satuan_jual;
        $this->harga_jual = $produk->harga_jual;

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

    // --- LOGIKA IMPORT (DIPERTAHANKAN) ---
    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:102400',
        ]);

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) {
                throw new \Exception("File gagal disimpan di server.");
            }

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