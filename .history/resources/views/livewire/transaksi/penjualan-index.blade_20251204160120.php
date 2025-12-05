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
    public $isLaporanMode = false;

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,csv,xls|max:102400',
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

    public function import()
    {
        if (!$this->file) {
            $this->addError('file', 'File Excel wajib dipilih!');
            return;
        }

        $this->validate();
        $filename = null;

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) {
                throw new \Exception("File gagal disimpan di server.");
            }

            // Proses Service
            $importService = new PenjualanImportService();
            $stats = $importService->handle($fullPath);

            // Bersihkan File
            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            // Reset Input
            $this->file = null;
            $this->iteration++;
            
            // FIX Laporan: Menambahkan skipped_empty ke pesan
            $msg = "Import Selesai! Total Baris: {$stats['total_rows']}. Sukses Masuk/Update: {$stats['imported']}.";
            
            if ($stats['skipped_empty'] > 0) {
                 $msg .= " (Peringatan: {$stats['skipped_empty']} baris dilewati karena Trans No/Kode Item kosong.)";
            }
            if ($stats['skipped_error'] > 0) {
                 $msg .= " (Error: {$stats['skipped_error']} baris dilewati karena kesalahan format data.)";
            }
            
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
    
    /**
     * Helper untuk menampilkan nilai negatif dalam kurung ( ) di tampilan.
     * Dipanggil langsung di view.
     */
    public function formatNegativeParentheses(string $value): string
    {
        $value = trim($value);

        if (str_starts_with($value, '-')) {
            return '(' . ltrim($value, '-') . ')';
        }
        return $value;
    }
}