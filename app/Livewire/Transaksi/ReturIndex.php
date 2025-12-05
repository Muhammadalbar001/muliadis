<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Transaksi\Retur;
use App\Services\Import\ReturImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReturIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $file;
    public $search = '';
    public $iteration = 1;

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,csv,xls|max:512000',
    ];

    public function render()
    {
        $retur = Retur::query()
            ->where('no_retur', 'like', '%' . $this->search . '%')
            ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orWhere('no_inv', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_retur', 'desc')
            ->paginate(10);
            
        return view('livewire.transaksi.retur-index', [
            'retur' => $retur
        ])->layout('layouts.app', ['header' => 'Retur Penjualan (Input Transaksi)']);
    }

    public function import()
    {
        if (empty($this->file)) {
            $this->addError('file', 'File Excel wajib dipilih atau upload belum selesai!');
            return;
        }

        $this->validate();
        $filename = null;

        try {
            ini_set('memory_limit', '-1');
            set_time_limit(0);

            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) {
                throw new \Exception("Gagal menyimpan file sementara.");
            }

            $importService = new ReturImportService();
            $stats = $importService->handle($fullPath);

            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            $this->file = null;
            $this->iteration++;
            
            $msg = "Import Selesai! Total: {$stats['total_rows']}. Sukses: {$stats['imported']}.";
            if ($stats['skipped_empty'] > 0) $msg .= " (Info: {$stats['skipped_empty']} kosong).";
            if ($stats['skipped_error'] > 0) $msg .= " (Error: {$stats['skipped_error']} format salah).";
            
            session()->flash('success', $msg);

        } catch (Throwable $e) {
            try {
                if (isset($filename) && Storage::disk('local')->exists($filename)) {
                    Storage::disk('local')->delete($filename);
                }
            } catch (Throwable $ex) {}

            $this->file = null;
            $this->iteration++;
            
            Log::error('Import Retur Gagal: ' . $e->getMessage());
            session()->flash('error', 'GAGAL: ' . $e->getMessage());
        }
    }
}