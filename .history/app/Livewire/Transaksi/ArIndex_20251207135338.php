<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Keuangan\AccountReceivable;
use App\Services\Import\ArImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ArIndex extends Component
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
        $query = AccountReceivable::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%'.$this->search.'%')
                  ->orWhere('pelanggan_name', 'like', '%'.$this->search.'%');
            });
        }

        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        $ar = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $optCabang = Cache::remember('ar_opt_cabang', 60, fn() => AccountReceivable::select('cabang')->distinct()->pluck('cabang'));

        return view('livewire.transaksi.ar-index', [
            'ar' => $ar,
            'optCabang' => $optCabang
        ])->layout('layouts.app', ['header' => 'Data AR (Piutang)']);
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

            $importService = new ArImportService();
            $stats = $importService->handle($fullPath);

            if (Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            $this->closeImportModal();
            Cache::forget('ar_opt_cabang');

            $msg = "Import AR Selesai!\n" .
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