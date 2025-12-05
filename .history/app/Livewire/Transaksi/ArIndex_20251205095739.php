<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Keuangan\AccountReceivable;
use App\Services\Import\ArImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class ArIndex extends Component
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
        $ar = AccountReceivable::query()
            ->where('no_penjualan', 'like', '%' . $this->search . '%')
            ->orWhere('pelanggan_name', 'like', '%' . $this->search . '%')
            ->orWhere('sales_name', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_penjualan', 'desc')
            ->paginate(10);
            
        return view('livewire.transaksi.ar-index', [
            'ar' => $ar
        ])->layout('layouts.app', ['header' => 'Daftar Piutang (AR)']);
    }

    public function import()
    {
        if (empty($this->file)) {
            $this->addError('file', 'File belum siap.');
            return;
        }

        $this->validate();
        $filename = null;

        try {
            ini_set('memory_limit', '-1');
            set_time_limit(0);

            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            $importService = new ArImportService();
            $stats = $importService->handle($fullPath);

            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            $this->file = null;
            $this->iteration++;
            
            $msg = "Selesai! Total: {$stats['total_rows']}. Sukses: {$stats['imported']}.";
            if ($stats['skipped_error'] > 0) $msg .= " (Gagal: {$stats['skipped_error']}).";
            
            session()->flash('success', $msg);

        } catch (Throwable $e) {
            if (isset($filename)) Storage::disk('local')->delete($filename);
            $this->file = null;
            $this->iteration++;
            
            Log::error('Import AR Gagal: ' . $e->getMessage());
            session()->flash('error', 'GAGAL: ' . $e->getMessage());
        }
    }
}