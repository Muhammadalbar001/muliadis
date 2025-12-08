<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Services\Import\SalesImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SalesIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $isImportOpen = false;
    public $file;
    public $iteration = 1;

    // Filter
    public $filterCity = '';
    public $filterDivisi = '';

    public function updatedSearch() { $this->resetPage(); }

    public function render()
    {
        $query = Sales::query();

        if ($this->search) {
            $query->where('sales_name', 'like', '%' . $this->search . '%');
        }
        
        if ($this->filterCity) $query->where('city', $this->filterCity);
        if ($this->filterDivisi) $query->where('divisi', $this->filterDivisi);

        $sales = $query->orderBy('sales_name')->paginate(10);
        
        // Cache Options
        $optCity   = Cache::remember('sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));
        $optDivisi = Cache::remember('sales_divisi', 3600, fn() => Sales::select('divisi')->distinct()->whereNotNull('divisi')->pluck('divisi'));

        return view('livewire.master.sales-index', [
            'sales' => $sales,
            'optCity' => $optCity,
            'optDivisi' => $optDivisi,
        ])->layout('layouts.app', ['header' => 'Master Salesman']);
    }

    // Import Logic
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; $this->iteration++; }
    
    public function import()
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,csv,xls|max:102400']);
        $filename = null;

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            $importService = new SalesImportService();
            // Optional: Truncate jika ingin data bersih setiap import
            // \App\Models\Master\Sales::truncate(); 
            
            $stats = $importService->handle($fullPath);

            if (Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            $this->closeImportModal();
            Cache::forget('sales_city'); Cache::forget('sales_divisi');

            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => "Total: {$stats['total_rows']} | Masuk: {$stats['processed']}"]);
        } catch (\Exception $e) {
            if ($filename) Storage::disk('local')->delete($filename);
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }
    
    public function delete($id)
    {
        Sales::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Data Sales dihapus']);
    }
}