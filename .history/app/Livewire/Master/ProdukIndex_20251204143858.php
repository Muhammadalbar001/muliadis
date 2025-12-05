<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Produk;
use App\Services\Import\ProdukImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable; // Import Throwable

class ProdukIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $file; 
    public $search = '';
    public $iteration = 1; // Untuk reset input file

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,csv,xls|max:102400', 
    ];

    public function render()
    {
        $produks = Produk::query()
            ->where('name_item', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // KODE KRITIS: Memanggil layout utama (layouts.app)
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

        $filename = null;

        try {
            // 2. Simpan File
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) {
                throw new \Exception("File gagal disimpan di server. Cek permissions folder storage.");
            }

            // 3. Proses Service
            $importService = new ProdukImportService();
            $stats = $importService->handle($fullPath);

            // 4. Bersihkan File
            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            // 5. Reset Input
            $this->file = null;
            $this->iteration++;
            
            // 6. Buat Pesan Laporan Detil
            $msg = "Import Selesai! ";
            $msg .= "Total Baris: {$stats['total_rows']}. ";
            $msg .= "Sukses Masuk/Update: {$stats['imported']}. ";
            if ($stats['skipped_error'] > 0) {
                $msg .= "(Peringatan: {$stats['skipped_error']} baris dilewati karena Error Data).";
            }
            
            session()->flash('success', $msg);

        } catch (Throwable $e) {
            // Error Handling Clean up
            try {
                if (isset($filename) && Storage::disk('local')->exists($filename)) {
                    Storage::disk('local')->delete($filename);
                }
            } catch (Throwable $ex) {}

            $this->file = null;
            $this->iteration++;
            
            Log::error('Import Gagal: ' . $e->getMessage());
            session()->flash('error', 'GAGAL: ' . $e->getMessage());
        }
    }
}