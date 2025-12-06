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
    public $isImportOpen = false; // Modal Khusus Import Excel
    
    // --- Properties Form Manual (Untuk Edit Data) ---
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
        // Query Produk (Sesuaikan dengan kolom database)
        // Kita cari berdasarkan Nama Item, SKU, atau Cabang
        $produks = Produk::query()
            ->where('name_item', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%') 
            ->orWhere('cabang', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Menampilkan 10 data per halaman

        return view('livewire.master.produk-index', [
            'produks' => $produks
        ])->layout('layouts.app', ['header' => 'Master Produk (Import Center)']);
    }

    // ==========================================
    // BAGIAN 1: LOGIKA IMPORT (UTAMA)
    // ==========================================

    public function openImportModal()
    {
        $this->resetErrorBag(); // Hapus pesan error sebelumnya
        $this->isImportOpen = true;
    }

    public function closeImportModal()
    {
        $this->isImportOpen = false;
        $this->file = null;
        $this->iteration++; // Reset input file di view
    }

    public function import()
    {
        // 1. Validasi File
        $this->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:102400', // Max 100MB
        ]);

        $filename = null;

        try {
            // 2. Simpan file sementara ke folder 'temp-imports'
            $filename = $this->file->store('temp-imports', 'local');
            
            // Ambil Full Path agar bisa dibaca oleh Library Excel
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) {
                throw new \Exception("Gagal menyimpan file sementara di server.");
            }

            // 3. Panggil Service Import
            $importService = new ProdukImportService();
            $stats = $importService->handle($fullPath);

            // 4. Hapus file temporary setelah selesai
            if (Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            $this->closeImportModal();

            // 5. BUAT PESAN NOTIFIKASI DETAIL
            // Contoh: "Selesai! Diproses: 14,400 baris. (Total Excel: 14,400)"
            $message = "Selesai! Berhasil diproses: " . number_format($stats['processed']) . 
                       " dari Total: " . number_format($stats['total_rows']);
            
            // Tambahkan info jika ada yang di-skip (Opsional, harusnya 0 dengan logic baru)
            if ($stats['skipped_empty'] > 0) {
                $message .= " | Skipped (Empty): " . number_format($stats['skipped_empty']);
            }
            if ($stats['skipped_error'] > 0) {
                $message .= " | Error: " . number_format($stats['skipped_error']);
            }

            session()->flash('success', $message);

        } catch (\Exception $e) {
            // Cleanup: Hapus file jika terjadi error di tengah jalan
            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }

            Log::error('Import Gagal: ' . $e->getMessage());
            $this->addError('file', 'Terjadi Kesalahan: ' . $e->getMessage());
        }
    }

    // ==========================================
    // BAGIAN 2: LOGIKA MANUAL (CRUD)
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
        
        // Mapping Data Database ke Form Input
        $this->kode_item   = $produk->sku;
        $this->nama_item   = $produk->name_item;
        $this->satuan_jual = $produk->oum;
        $this->harga_jual  = $produk->buy;

        $this->isInputOpen = true;
    }

    public function store()
    {
        $this->validate([
            // Validasi SKU unik, kecuali untuk ID yang sedang diedit
            'kode_item' => 'required|unique:produks,sku,' . $this->productId,
            'nama_item' => 'required',
        ]);

        Produk::updateOrCreate(['id' => $this->productId], [
            'sku'       => $this->kode_item,
            'name_item' => $this->nama_item,
            'oum'       => $this->satuan_jual,
            'buy'       => $this->harga_jual ?? 0, 
            'stok'      => '0', // Default stok 0 untuk input manual
        ]);

        session()->flash('success', $this->productId ? 'Produk berhasil diperbarui.' : 'Produk berhasil ditambahkan.');
        $this->closeInputModal();
    }

    public function delete($id)
    {
        try {
            Produk::find($id)->delete();
            session()->flash('success', 'Produk berhasil dihapus.');
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