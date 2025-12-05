<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Transaksi\Penjualan;
use App\Services\Import\PenjualanImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class PenjualanIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $file;
    public $search = '';
    public $iteration = 1;
    // $isLaporanMode dihapus karena sudah dipisah komponennya

    // Rule validasi 500MB
    protected $rules = [
        'file' => 'required|file|mimes:xlsx,csv,xls|max:512000',
    ];

    public function render()
    {
        $penjualan = Penjualan::query()
            ->where('trans_no', 'like', '%' . $this->search . '%')
            ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_penjualan', 'desc')
            ->paginate(10);
            
        return view('livewire.transaksi.penjualan-index', [
            'penjualan' => $penjualan
        ])->layout('layouts.app', ['header' => 'Order Penjualan (Input Transaksi)']);
    }

    public function import()
    {
        // 1. CEK MANUAL: Pastikan file sudah ada
        if (empty($this->file)) {
            $this->addError('file', 'File Excel wajib dipilih atau upload belum selesai!');
            return;
        }

        // 2. Validasi File
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

            $importService = new PenjualanImportService();
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
            
            Log::error('Import Penjualan Gagal: ' . $e->getMessage());
            session()->flash('error', 'GAGAL: ' . $e->getMessage());
        }
    }
}