<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Services\Import\SalesImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class SalesIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $isImportOpen = false;
    public $file;
    public $iteration = 1;
    
    // Opsi Reset Data
    public $resetData = false; 

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

    // --- IMPORT LOGIC ---
    public function openImportModal() { 
        $this->resetErrorBag(); 
        $this->isImportOpen = true; 
        $this->resetData = false; // Default jangan hapus data lama
    }
    
    public function closeImportModal() { 
        $this->isImportOpen = false; 
        $this->file = null; 
        $this->iteration++; 
    }
    
    public function import()
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,csv,xls|max:102400']);
        $filename = null;

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            // 1. Reset Data Lama (Opsional, tapi disarankan untuk Master Data Sales agar bersih)
            if ($this->resetData) {
                DB::table('sales')->truncate();
            }

            // 2. Proses Import
            $importService = new SalesImportService();
            $stats = $importService->handle($fullPath);

            // 3. Cleanup
            if (Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            $this->closeImportModal();
            
            // Hapus cache filter agar data baru muncul di dropdown
            Cache::forget('sales_city'); 
            Cache::forget('sales_divisi');
            Cache::forget('dash_sales'); // Reset cache filter di Dashboard juga

            $msg = "Import Sales Selesai!\n" .
                   "📊 Total Baris: " . number_format($stats['total_rows']) . "\n" .
                   "✅ Masuk Database: " . number_format($stats['processed']);

            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => $msg]);

        } catch (\Exception $e) {
            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }
    
    public function delete($id)
    {
        try {
            Sales::destroy($id);
            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Data Sales dihapus']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Gagal menghapus data']);
        }
    }
}