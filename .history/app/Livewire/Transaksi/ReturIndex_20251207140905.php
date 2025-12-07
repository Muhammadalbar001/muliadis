<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Transaksi\Retur;
use App\Services\Import\ReturImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ReturIndex extends Component
{
    use WithFileUploads, WithPagination;

    // --- Properties ---
    public $search = '';
    
    // Filter Multi-Select
    public $filterCabang = [];
    public $filterSales = [];
    public $filterSupplier = [];
    public $filterStatus = [];

    // Import
    public $isImportOpen = false;
    public $file;
    public $iteration = 1;

    // Reset Pagination saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }
    public function updatedFilterSupplier() { $this->resetPage(); }
    public function updatedFilterStatus() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->filterCabang = [];
        $this->filterSales = [];
        $this->filterSupplier = [];
        $this->filterStatus = [];
        $this->resetPage();
    }

    public function render()
    {
        $query = Retur::query();

        // 1. Search Global
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
                  ->orWhere('no_inv', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter Multi-Select
        $query->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
              ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales))
              ->when(!empty($this->filterSupplier), fn($q) => $q->whereIn('supplier', $this->filterSupplier))
              ->when(!empty($this->filterStatus), fn($q) => $q->whereIn('status', $this->filterStatus));

        $returs = $query->orderBy('tgl_retur', 'desc')->paginate(10);

        // 3. Ambil Opsi Filter (Cache 1 Jam)
        $optCabang   = Cache::remember('retur_opt_cabang', 3600, fn() => Retur::select('cabang')->distinct()->whereNotNull('cabang')->orderBy('cabang')->pluck('cabang'));
        $optSales    = Cache::remember('retur_opt_sales', 3600, fn() => Retur::select('sales_name')->distinct()->whereNotNull('sales_name')->orderBy('sales_name')->pluck('sales_name'));
        $optSupplier = Cache::remember('retur_opt_supplier', 3600, fn() => Retur::select('supplier')->distinct()->whereNotNull('supplier')->orderBy('supplier')->pluck('supplier'));
        $optStatus   = Cache::remember('retur_opt_status', 3600, fn() => Retur::select('status')->distinct()->whereNotNull('status')->orderBy('status')->pluck('status'));

        return view('livewire.transaksi.retur-index', [
            'returs'      => $returs,
            'optCabang'   => $optCabang,
            'optSales'    => $optSales,
            'optSupplier' => $optSupplier,
            'optStatus'   => $optStatus,
        ])->layout('layouts.app', ['header' => 'Data Retur Penjualan']);
    }

    // --- LOGIKA IMPORT (Tetap Sama) ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; $this->iteration++; }

    public function import()
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,csv,xls|max:102400']);
        $filename = null;

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            $importService = new ReturImportService();
            $stats = $importService->handle($fullPath);

            if (Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            $this->closeImportModal();
            
            // Clear Cache Filter
            Cache::forget('retur_opt_cabang');
            Cache::forget('retur_opt_sales');
            Cache::forget('retur_opt_supplier');
            Cache::forget('retur_opt_status');

            $msg = "Retur Selesai!\n" .
                   "📊 Total Baris: " . number_format($stats['total_rows']) . "\n" .
                   "✅ Masuk Database: " . number_format($stats['processed']) . "\n" .
                   "🗑️ Skipped: " . number_format($stats['skipped_no_item']);

            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => $msg]);

        } catch (\Exception $e) {
            if ($filename) Storage::disk('local')->delete($filename);
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }
}