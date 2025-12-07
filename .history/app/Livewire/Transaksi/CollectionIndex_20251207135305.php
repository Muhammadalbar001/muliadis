<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Keuangan\Collection;
use App\Services\Import\CollectionImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CollectionIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $filterCabang = [];
    public $isImportOpen = false;
    public $file;
    public $iteration = 1;

    public function updatedSearch() { $this->resetPage(); }

    public function render()
    {
        $query = Collection::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('receive_no', 'like', '%'.$this->search.'%')
                  ->orWhere('invoice_no', 'like', '%'.$this->search.'%')
                  ->orWhere('outlet_name', 'like', '%'.$this->search.'%');
            });
        }

        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        $collections = $query->orderBy('created_at', 'desc')->paginate(10);
        $optCabang = Cache::remember('col_opt_cabang', 60, fn() => Collection::select('cabang')->distinct()->pluck('cabang'));

        return view('livewire.transaksi.collection-index', [
            'collections' => $collections,
            'optCabang' => $optCabang
        ])->layout('layouts.app', ['header' => 'Data Collection (Pelunasan)']);
    }

    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; $this->iteration++; }

    public function import()
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,csv,xls|max:102400']);
        $filename = null;

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            $importService = new CollectionImportService();
            $stats = $importService->handle($fullPath);

            if (Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            $this->closeImportModal();
            Cache::forget('col_opt_cabang');

            $msg = "Import Collection Selesai!\n" .
                   "📊 Total Baris: " . number_format($stats['total_rows']) . "\n" .
                   "✅ Masuk Database: " . number_format($stats['processed']);

            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => $msg]);

        } catch (\Exception $e) {
            if ($filename) Storage::disk('local')->delete($filename);
            Log::error($e);
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }
}