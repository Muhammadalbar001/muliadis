<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads; // Wajib untuk upload
use Livewire\WithPagination;
use App\Models\Transaksi\Penjualan;
use App\Services\Import\PenjualanImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class PenjualanIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $file; // Variabel penampung file
    public $search = '';
    public $iteration = 1; // Trik reset file input
    public $isLaporanMode = false;

    // Settingan validasi file (500MB)
    protected $rules = [
        'file' => 'required|file|mimes:xlsx,csv,xls|max:512000',
    ];

    public function mount()
    {
        $this->isLaporanMode = request()->routeIs('laporan.rekap_penjualan');
    }

    public function render()
    {
        $penjualan = Penjualan::query()
            ->where('trans_no', 'like', '%' . $this->search . '%')
            ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_penjualan', 'desc')
            ->paginate(10);
            
        $header = $this->isLaporanMode
                  ? 'Rekapitulasi Penjualan (Laporan)' 
                  : 'Order Penjualan (Input Transaksi)';

        return view('livewire.transaksi.penjualan-index', [
            'penjualan' => $penjualan
        ])->layout('layouts.app', ['header' => $header]);
    }

    // Fungsi ini otomatis jalan saat file selesai diupload ke temp
    public function updatedFile()
    {
        $this->validate([
            'file' => 'file|max:512000', // Cek size doang biar cepet
        ]);
    }

    public function import()
    {
        // Validasi Final
        $this->validate();

        $filename = null;

        try {
            // Setup Memory Unlimited
            ini_set('memory_limit', '-1');
            set_time_limit(0);

            // 1. Simpan File
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            // 2. Proses Service
            $importService = new PenjualanImportService();
            $stats = $importService->handle($fullPath);

            // 3. Hapus File Temp
            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            // 4. Reset Input & UI
            $this->file = null;
            $this->iteration++;
            
            $msg = "Sukses! Total: {$stats['total_rows']}. Masuk: {$stats['imported']}.";
            if ($stats['skipped_empty'] > 0) $msg .= " (Skip Kosong: {$stats['skipped_empty']}).";
            if ($stats['skipped_error'] > 0) $msg .= " (Skip Error: {$stats['skipped_error']}).";
            
            session()->flash('success', $msg);

        } catch (Throwable $e) {
            // Cleanup jika error
            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            $this->file = null;
            $this->iteration++;

            Log::error('Import Gagal: ' . $e->getMessage());
            session()->flash('error', 'GAGAL: ' . $e->getMessage());
        }
    }

    public function formatNegativeParentheses(string $value): string
    {
        $value = trim($value);
        if (str_starts_with($value, '-')) {
            return '(' . ltrim($value, '-') . ')';
        }
        return $value;
    }
}