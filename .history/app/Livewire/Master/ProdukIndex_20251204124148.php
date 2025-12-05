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

    public $file;
    public $search = '';
    public $iteration = 1; // Trik untuk reset input file

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,csv,xls|max:51200',
    ];

    public function render()
    {
        $produks = Produk::query()
            ->where('name_item', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.master.produk-index', [
            'produks' => $produks
        ])->layout('layouts.app', ['header' => 'Manajemen Produk']);
    }

    public function import()
    {
        // 1. CEK MANUAL: Apakah file ada di variabel sementara Livewire?
        if (!$this->file) {
            $this->addError('file', 'File Excel wajib dipilih sebelum klik Import!');
            session()->flash('error', 'File Excel belum dipilih!');
            return;
        }

        // 2. Validasi Standar
        $this->validate();

        $path = null;

        try {
            // 3. Simpan Sementara
            // 'local' memastikan file tersimpan di storage/app/temp-imports
            $path = $this->file->store('temp-imports', 'local'); 
            $fullPath = storage_path('app/' . $path);

            // 4. Proses Service
            $importService = new ProdukImportService();
            $count = $importService->handle($fullPath);

            // 5. Bersihkan
            if ($path && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
            
            // 6. Reset Input File (Penting!)
            $this->file = null;
            $this->iteration++; // Ganti ID input agar browser mereset field
            
            session()->flash('success', "BERHASIL! $count produk telah diimport.");

        } catch (\Exception $e) {
            // Error Handling
            if ($path && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
            $this->file = null;
            $this->iteration++;
            
            Log::error('Import Gagal: ' . $e->getMessage());
            session()->flash('error', 'GAGAL: ' . $e->getMessage());
        }
    }
}