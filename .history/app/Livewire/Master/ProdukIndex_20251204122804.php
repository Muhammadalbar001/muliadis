<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Produk;
use App\Services\Import\ProdukImportService; // Pastikan Service di-import
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProdukIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $file; 
    public $search = '';

    protected $rules = [
        'file' => 'required|mimes:xlsx,csv,xls|max:102400', // Max 100MB
    ];

    protected $messages = [
        'file.required' => 'Mohon pilih file Excel terlebih dahulu.',
        'file.mimes' => 'Format file wajib Excel (.xlsx, .csv, atau .xls).',
        'file.max' => 'Ukuran file terlalu besar (Maks 100MB).',
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

    // Method Import yang bersih, memanggil Service
    public function import(ProdukImportService $importService)
    {
        $this->validate();

        // Variabel path diinisialisasi null untuk error handling
        $path = null;

        try {
            // 1. Simpan file sementara
            $path = $this->file->store('temp-imports');
            $fullPath = storage_path('app/' . $path);

            // 2. Panggil Service (Logic Index-Based ada di sini sekarang)
            $count = $importService->handle($fullPath);

            // 3. Bersihkan file temp & reset input
            Storage::delete($path);
            $this->file = null; 
            
            // 4. Beri Notifikasi Sukses
            session()->flash('success', "Sukses! $count Produk berhasil di-import.");

        } catch (\Exception $e) {
            // Error Handling
            if ($path) Storage::delete($path);
            $this->file = null;
            
            Log::error('Import Livewire Error: ' . $e->getMessage());
            
            // Tampilkan pesan error ke layar user
            session()->flash('error', 'Gagal: ' . $e->getMessage());
        }
    }
}