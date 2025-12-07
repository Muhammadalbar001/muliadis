<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Transaksi\Penjualan;
use App\Services\Import\PenjualanImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PenjualanIndex extends Component
{
    use WithFileUploads, WithPagination;

    // Properties
    public $search = '';
    public $isImportOpen = false;
    public $file;
    public $iteration = 1;

    // Filters
    public $filterCabang = [];
    public $filterSales = [];
    
    public function updatedSearch() { $this->resetPage(); }

    public function render()
    {
        $query = Penjualan::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }

        // Filter Cabang
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        $penjualans = $query->orderBy('tgl_penjualan', 'desc')
                            ->orderBy('trans_no', 'desc')
                            ->paginate(10);

        // Options Filter
        $optCabang = Cache::remember('penjualan_opt_cabang', 60, fn() => Penjualan::select('cabang')->distinct()->pluck('cabang'));

        return view('livewire.transaksi.penjualan-index', [
            'penjualans' => $penjualans,
            'optCabang' => $optCabang
        ])->layout('layouts.app', ['header' => 'Data Penjualan']);
    }

    // --- IMPORT ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; $this->iteration++; }

    public function import()
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,csv,xls|max:102400']);
        $filename = null;

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            $importService = new PenjualanImportService();
            $stats = $importService->handle($fullPath);

            if (Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            $this->closeImportModal();
            Cache::forget('penjualan_opt_cabang'); // Reset cache filter

            $msg = "Selesai! Total: " . number_format($stats['total_rows']) . "\n" .
                   "✅ Masuk DB: " . number_format($stats['processed']);

            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Import Sukses', 'message' => $msg]);

        } catch (\Exception $e) {
            $importService = new PenjualanImportService();
    $stats = $importService->handle($fullPath);

    // ... Hapus file ...

    $msg = "Selesai!\n" . 
            "📂 Total Baris Excel: " . number_format($stats['total_rows']) . "\n" .
            "✅ Masuk Database: " . number_format($stats['processed']);
    
    if ($stats['skipped_empty'] > 0) {
        $msg .= "\n⚠️ Skipped (Kosong): " . number_format($stats['skipped_empty']);
    }

    $this->dispatch('show-toast', [
        'type' => 'success',
        'title' => 'Import Sukses',
        'message' => $msg
    ]);
        }
    }
    
    public function delete($id) {
        Penjualan::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Data dihapus.']);
    }
}