<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Produk;
use App\Services\Import\ProdukImportService; // Import Service Baru
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProdukIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $file; 
    public $search = '';

    protected $rules = [
        'file' => 'required|mimes:xlsx,csv,xls|max:102400', 
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

    // Kita inject Service di parameter method import
    public function import(ProdukImportService $importService)
    {
        dd("Masuk Fungsi Import", $this->file);
        $this->validate();

        try {
            // 1. Simpan file sementara
            $path = $this->file->store('temp-imports');
            $fullPath = storage_path('app/' . $path);

            // 2. Panggil Service untuk proses berat
            // Livewire tidak perlu tahu detail kolom Excel, biar Service yang urus
            $count = $importService->handle($fullPath);

            // 3. Bersihkan file temp
            Storage::delete($path);
            $this->file = null; 
            
            session()->flash('success', "Sukses! $count Produk berhasil di-import.");

        } catch (\Exception $e) {
            // Error Handling
            if (isset($path)) Storage::delete($path);
            $this->file = null;
            Log::error('Import Error: ' . $e->getMessage());
            session()->flash('error', 'Gagal Import: ' . $e->getMessage());
        }
    }
}