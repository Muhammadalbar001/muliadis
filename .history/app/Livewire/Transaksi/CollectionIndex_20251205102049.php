<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Keuangan\Collection;
use App\Services\Import\CollectionImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class CollectionIndex extends Component
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
        $collections = Collection::query()
            ->where('receive_no', 'like', '%' . $this->search . '%')
            ->orWhere('outlet_name', 'like', '%' . $this->search . '%')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
            
        return view('livewire.transaksi.collection-index', [
            'collections' => $collections
        ])->layout('layouts.app', ['header' => 'Collection (Pelunasan)']);
    }

    public function import()
    {
        if (empty($this->file)) {
            $this->addError('file', 'File Excel wajib dipilih!');
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
                throw new \Exception("Gagal menyimpan file.");
            }

            $importService = new CollectionImportService();
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
            
            Log::error('Import Collection Gagal: ' . $e->getMessage());
            session()->flash('error', 'GAGAL: ' . $e->getMessage());
        }
    }
}