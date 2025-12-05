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
    public $isLaporanMode = false;

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,csv,xls|max:512000',
    ];

    public function mount()
    {
        $this->isLaporanMode = request()->routeIs('laporan.rekap_retur');
    }

    public function render()
    {
        $retur = Retur::query()
            ->where('no_retur', 'like', '%' . $this->search . '%')
            ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orWhere('no_inv', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_retur', 'desc')
            ->paginate(10);
            
        $header = $this->isLaporanMode
                  ? 'Rekapitulasi Retur (Laporan)' 
                  : 'Retur Penjualan (Input Transaksi)';

        return view('livewire.transaksi.retur-index', [
            'retur' => $retur
        ])->layout('layouts.app', ['header' => $header]);
    }

    public function import()
    {
        if (empty($this->file)) {
            $this->addError('file', 'File belum siap. Tunggu upload selesai.');
            return;
        }

        $this->validate();
        $filename = null;

        try {
            ini_set('memory_limit', '-1');
            set_time_limit(0);

            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            $importService = new ReturImportService();
            $stats = $importService->handle($fullPath);

            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            $this->file = null;
            $this->iteration++;
            
            $msg = "Selesai! Total: {$stats['total_rows']}. Sukses: {$stats['imported']}.";
            
            if ($stats['skipped_error'] > 0) {
                // TAMPILKAN PESAN ERROR TEKNIS AGAR USER TAHU SEBABNYA
                $msg .= " GAGAL: {$stats['skipped_error']} baris. Contoh Error: " . $stats['last_error_msg'];
            }
            
            session()->flash('success', $msg);

        } catch (Throwable $e) {
            if (isset($filename)) Storage::disk('local')->delete($filename);
            $this->file = null;
            $this->iteration++;
            
            Log::error('Import Retur Gagal: ' . $e->getMessage());
            session()->flash('error', 'CRITICAL ERROR: ' . $e->getMessage());
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