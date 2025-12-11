<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Keuangan\AccountReceivable;
use App\Services\Import\ArImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage; // <--- WAJIB IMPORT INI
use Illuminate\Support\Facades\DB;

class ArIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterCabang = [];
    public $filterSales = [];
    public $filterUmur = ''; 

    // Modal Import
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }
    public function updatedFilterUmur() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['search', 'filterCabang', 'filterSales', 'filterUmur']);
        $this->resetPage();
    }

    public function render()
    {
        $query = AccountReceivable::query();

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%'.$this->search.'%')
                  ->orWhere('pelanggan_name', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }

        // 2. Filters
        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);
        if (!empty($this->filterSales)) $query->whereIn('sales_name', $this->filterSales);

        if ($this->filterUmur === 'lancar') {
            $query->where('umur_piutang', '<=', 30);
        } elseif ($this->filterUmur === 'macet') {
            $query->where('umur_piutang', '>', 30);
        }

        // --- SUMMARY ---
        $summary = [
            'total_piutang' => (clone $query)->sum('nilai'),
            'total_faktur'  => (clone $query)->count(),
            'total_macet'   => (clone $query)->where('umur_piutang', '>', 30)->sum('nilai'),
        ];

        // 3. Table Data
        $ars = $query->orderBy('umur_piutang', 'desc')->paginate(10);

        $optCabang = Cache::remember('opt_ar_cabang', 3600, fn() => AccountReceivable::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales = Cache::remember('opt_ar_sales', 3600, fn() => AccountReceivable::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.transaksi.ar-index', compact('ars', 'optCabang', 'optSales', 'summary'))
            ->layout('layouts.app', ['header' => 'Monitoring Piutang (AR)']);
    }

    // --- IMPORT LOGIC (UPDATED) ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() 
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:153600'
        ]);

        // 1. Lock Process
        $lock = Cache::lock('importing_ar', 600);
        if (!$lock->get()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Import sedang berjalan. Tunggu sebentar!']);
            return;
        }

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            // 2. Simpan File
            $path = $this->file->store('temp-import', 'local');
            // 3. Fix Path Windows
            $fullPath = Storage::disk('local')->path($path);

            // 4. Run Service
            $stats = (new ArImportService)->handle($fullPath, $this->resetData); 
            $count = $stats['processed'] ?? 0;
            $skipped = $stats['skipped_empty'] ?? 0;

            // 5. Cleanup
            if(Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            Cache::forget('opt_ar_cabang');
            Cache::forget('opt_ar_sales');
            $this->closeImportModal();
            
            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => "BERHASIL! Masuk: " . number_format($count) . " Data Piutang. (Skip: $skipped)"
            ]);

        } catch (\Exception $e) {
            if(isset($path) && Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        } finally {
            $lock->release();
        }
    }

    public function delete($id) {
        AccountReceivable::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data AR dihapus']);
    }
}