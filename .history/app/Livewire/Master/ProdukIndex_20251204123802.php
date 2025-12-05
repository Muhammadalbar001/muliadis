<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Produk;
use App\Services\Import\ProdukImportService; // Pastikan ini ada
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProdukIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $file; 
    public $search = '';

    // Validasi Dasar
    protected $rules = [
        'file' => 'required|mimes:xlsx,csv,xls|max:51200', // 50MB
    ];

    public function render()
    {
        $produks = Produk::query()
            ->where('name_item', 'like', '%'.$this->search.'%')
            ->orWhere('sku', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.master.produk-index', [
            'produks' => $produks
        ])->layout('layouts.app', ['header' => 'Manajemen Produk']);
    }

    // Perubahan: Hapus parameter Service, kita panggil manual di dalam
    public function import()
    {
        // 1. Cek Manual apakah file ada
        if (!$this->file) {
            session()->flash('error', 'File Excel belum dipilih!');
            return;
        }

        // 2. Validasi File
        $this->validate();

        $path = null;

        try {
            // 3. Simpan File Sementara
            $path = $this->file->store('temp-imports');
            $fullPath = storage_path('app/' . $path);

            // 4. Panggil Service Manual (Lebih Aman)
            $importService = new ProdukImportService();
            $count = $importService->handle($fullPath);

            // 5. Bersihkan
            if(Storage::exists($path)) {
                Storage::delete($path);
            }
            $this->file = null; // Reset input file
            
            // 6. Pesan Sukses
            session()->flash('success', "BERHASIL! $count data produk telah masuk.");

        } catch (\Exception $e) {
            // Error Handling
            if ($path && Storage::exists($path)) {
                Storage::delete($path);
            }
            $this->file = null; // Reset input file meski gagal
            
            Log::error('Import Gagal: ' . $e->getMessage());
            session()->flash('error', 'GAGAL: ' . $e->getMessage());
        }
    }
}