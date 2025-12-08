<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget; // Model Baru
use App\Services\Import\SalesImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SalesIndex extends Component
{
    use WithFileUploads, WithPagination;

    // ... (Properties lama tetap ada: search, filterCity, dll) ...
    public $search = '';
    public $filterCity = '';
    public $filterDivisi = '';

    // Import
    public $isImportOpen = false;
    public $file;
    public $iteration = 1;
    public $resetData = false;

    // --- PROPERTI BARU UNTUK TARGET ---
    public $isTargetOpen = false;
    public $selectedSalesId;
    public $selectedSalesName;
    public $targetYear;
    
    // Array untuk menampung inputan 12 bulan [bulan => ['ims' => 0, 'oa' => 0]]
    public $targets = []; 

    public function mount()
    {
        $this->targetYear = date('Y'); // Default tahun ini
    }

    public function updatedSearch() { $this->resetPage(); }

    public function render()
    {
        // ... (Query render SAMA SEPERTI SEBELUMNYA) ...
        $query = Sales::query();
        if ($this->search) $query->where('sales_name', 'like', '%' . $this->search . '%');
        if ($this->filterCity) $query->where('city', $this->filterCity);
        if ($this->filterDivisi) $query->where('divisi', $this->filterDivisi);

        $sales = $query->orderBy('sales_name')->paginate(10);
        
        $optCity   = Cache::remember('sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));
        $optDivisi = Cache::remember('sales_divisi', 3600, fn() => Sales::select('divisi')->distinct()->whereNotNull('divisi')->pluck('divisi'));

        return view('livewire.master.sales-index', [
            'sales' => $sales,
            'optCity' => $optCity,
            'optDivisi' => $optDivisi,
        ])->layout('layouts.app', ['header' => 'Master Salesman']);
    }

    // --- LOGIC TARGET BULANAN ---

    public function openTargetModal($id)
    {
        $sales = Sales::find($id);
        $this->selectedSalesId = $sales->id;
        $this->selectedSalesName = $sales->sales_name;
        $this->isTargetOpen = true;

        $this->loadTargets();
    }

    public function updatedTargetYear()
    {
        $this->loadTargets(); // Reload data jika tahun diganti di modal
    }

    public function loadTargets()
    {
        // Ambil data target dari DB berdasarkan Sales ID & Tahun
        $existingTargets = SalesTarget::where('sales_id', $this->selectedSalesId)
            ->where('year', $this->targetYear)
            ->get()
            ->keyBy('month'); // Index array berdasarkan bulan (1-12)

        // Siapkan array kosong untuk 12 bulan
        $this->targets = [];
        
        for ($i = 1; $i <= 12; $i++) {
            if (isset($existingTargets[$i])) {
                $this->targets[$i] = [
                    'ims' => (int) $existingTargets[$i]->target_ims,
                    'oa'  => (int) $existingTargets[$i]->target_oa
                ];
            } else {
                // Jika belum ada, set 0
                $this->targets[$i] = ['ims' => 0, 'oa' => 0];
            }
        }
    }

    public function saveTargets()
    {
        foreach ($this->targets as $month => $data) {
            SalesTarget::updateOrCreate(
                [
                    'sales_id' => $this->selectedSalesId,
                    'year' => $this->targetYear,
                    'month' => $month
                ],
                [
                    'target_ims' => $data['ims'],
                    'target_oa' => $data['oa']
                ]
            );
        }

        $this->isTargetOpen = false;
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Tersimpan', 'message' => 'Target Sales berhasil diupdate!']);
    }

    public function closeTargetModal()
    {
        $this->isTargetOpen = false;
    }

    // ... (Import & Delete Logic SAMA SEPERTI SEBELUMNYA) ...
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; $this->resetData = false; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; $this->iteration++; }
    public function import() { 
        // ... (Copy paste kode import sebelumnya) ... 
        $this->validate(['file' => 'required|file|mimes:xlsx,csv,xls|max:102400']);
        // ... dst
        $filename = null;
        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);
            if ($this->resetData) { DB::table('sales')->truncate(); }
            $importService = new SalesImportService();
            $stats = $importService->handle($fullPath);
            if (Storage::disk('local')->exists($filename)) Storage::disk('local')->delete($filename);
            $this->closeImportModal();
            Cache::forget('sales_city'); Cache::forget('sales_divisi'); Cache::forget('dash_sales');
            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => "Total: {$stats['total_rows']} | Masuk: {$stats['processed']}"]);
        } catch (\Exception $e) {
            if ($filename) Storage::disk('local')->delete($filename);
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }
    public function delete($id) { 
        Sales::destroy($id); 
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Data Sales dihapus']); 
    }
    // ...
class SalesIndex extends Component
{
    // ... kode lain ...

    // Tambahkan Fungsi ini
    
    
    // ...
}
}