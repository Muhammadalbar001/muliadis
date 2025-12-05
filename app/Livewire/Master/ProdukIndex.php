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
    public $iteration = 1;

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,csv,xls|max:102400', // 100MB
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
        // 1. Cek Manual
        if (!$this->file) {
            $this->addError('file', 'File Excel wajib dipilih!');
            return;
        }

        $this->validate();

        $path = null;

        try {
            // 2. Simpan File (Return value berupa path relatif: "temp-imports/namafile.xlsx")
            $filename = $this->file->store('temp-imports', 'local');
            
            // 3. Ambil Full Path yang Aman untuk Windows (FIX UTAMA DISINI)
            // Jangan pakai storage_path('app/' . $filename) karena bikin slash error
            $fullPath = Storage::disk('local')->path($filename);

            // Cek apakah file benar-benar tertulis di disk
            if (!file_exists($fullPath)) {
                throw new \Exception("File gagal disimpan di server. Cek permissions folder storage.");
            }

            // 4. Proses Service
            $importService = new ProdukImportService();
            $count = $importService->handle($fullPath);

            // 5. Bersihkan File
            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            // 6. Reset Input
            $this->file = null;
            $this->iteration++;
            
            session()->flash('success', "BERHASIL! $count produk telah diimport.");

        } catch (\Exception $e) {
            // Error Handling Clean up
            // Disini kita pakai try-catch lagi untuk delete agar tidak error tumpuk
            try {
                if (isset($filename) && Storage::disk('local')->exists($filename)) {
                    Storage::disk('local')->delete($filename);
                }
            } catch (\Exception $ex) {
                // Ignore delete error
            }

            $this->file = null;
            $this->iteration++;
            
            Log::error('Import Gagal: ' . $e->getMessage());
            session()->flash('error', 'GAGAL: ' . $e->getMessage());
        }
    }
}