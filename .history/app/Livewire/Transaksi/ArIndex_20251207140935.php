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
    
    // Filter Multi-Select
    public $filterCabang = [];
    public $filterSales = [];

    public $isImportOpen = false;
    public $file;
    public $iteration = 1;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->filterCabang = [];
        $this->filterSales = [];
        $this->resetPage();
    }

    public function render()
    {
        $query = AccountReceivable::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%' . $this->search . '%')
                  ->orWhere('pelanggan_name', 'like', '%' . $this->search . '%');
            });
        }

        $query->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
              ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales));

        $ars = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Cache Options
        $optCabang = Cache::remember('ar_opt_cabang', 3600, fn() => AccountReceivable::select('cabang')->distinct()->whereNotNull('cabang')->orderBy('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('ar_opt_sales', 3600, fn() => AccountReceivable::select('sales_name')->distinct()->whereNotNull('sales_name')->orderBy('sales_name')->pluck('sales_name'));

        return view('livewire.transaksi.ar-index', [
            'ars'       => $ars,
            'optCabang' => $optCabang,
            'optSales'  => $optSales,
        ])->layout('layouts.app', ['header' => 'Monitoring Piutang (AR)']);
    }
    
    // ... (Import Logic & Delete Logic TETAP SAMA seperti sebelumnya) ...
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; $this->iteration++; }
    public function import() { /* ... Copy kode import ArImportService sebelumnya ... */
        $this->validate(['file' => 'required|file|mimes:xlsx,csv,xls|max:102400']);
        $filename = null;
        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);
            $importService = new ArImportService();
            $stats = $importService->handle($fullPath);
            if (Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            $this->closeImportModal();
            Cache::forget('ar_opt_cabang'); Cache::forget('ar_opt_sales'); // Clear cache
            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => "Total: {$stats['total_rows']} | Masuk: {$stats['processed']}"]);
        } catch (\Exception $e) {
            if ($filename) Storage::disk('local')->delete($filename);
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }
    public function delete($id) { AccountReceivable::destroy($id); $this->dispatch('show-toast', ['type'=>'success','title'=>'Dihapus','message'=>'Data dihapus']); }
}