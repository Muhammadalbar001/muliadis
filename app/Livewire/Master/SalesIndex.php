<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Services\Import\SalesImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SalesIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    
    // FILTER MULTI-SELECT (ARRAY)
    public $filterCity = [];   
    public $filterDivisi = []; 
    public $filterStatus = 'Active';

    // MODAL FORM
    public $isOpen = false; 
    public $salesId;
    public $sales_name, $sales_code, $city, $divisi, $status;

    // MODAL IMPORT
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // MODAL TARGET
    public $isTargetOpen = false;
    public $selectedSalesName;
    public $targetYear;
    public $targets = []; 

    public function mount()
    {
        $this->targetYear = date('Y');
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCity() { $this->resetPage(); }
    public function updatedFilterDivisi() { $this->resetPage(); }

    // --- INI METHOD YANG SEBELUMNYA HILANG/ERROR ---
    public function resetFilters()
    {
        $this->reset(['search', 'filterCity', 'filterDivisi', 'filterStatus']);
        $this->resetPage();
    }
    // -----------------------------------------------

    public function render()
    {
        $query = Sales::query();

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('sales_name', 'like', '%' . $this->search . '%')
                  ->orWhere('sales_code', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter Multi-Select
        if (!empty($this->filterCity)) {
            $query->whereIn('city', $this->filterCity);
        }

        if (!empty($this->filterDivisi)) {
            $query->whereIn('divisi', $this->filterDivisi);
        }

        // 3. Filter Status
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $sales = $query->orderBy('sales_name')->paginate(25);

        // Cache Options
        $optCity = Cache::remember('opt_sales_city', 3600, fn() => Sales::select('city')->distinct()->whereNotNull('city')->pluck('city'));
        $optDivisi = Cache::remember('opt_sales_divisi', 3600, fn() => Sales::select('divisi')->distinct()->whereNotNull('divisi')->pluck('divisi'));

        return view('livewire.master.sales-index', compact('sales', 'optCity', 'optDivisi'))
            ->layout('layouts.app', ['header' => 'Master Salesman']);
    }

    // --- CRUD ---
    public function create() {
        $this->reset(['salesId', 'sales_name', 'sales_code', 'city', 'divisi', 'status']);
        $this->status = 'Active';
        $this->isOpen = true;
    }

    public function edit($id) {
        $sales = Sales::findOrFail($id);
        $this->salesId = $id;
        $this->sales_name = $sales->sales_name;
        $this->sales_code = $sales->sales_code;
        $this->city = $sales->city;
        $this->divisi = $sales->divisi;
        $this->status = $sales->status;
        $this->isOpen = true;
    }

    public function store() {
        $this->validate([
            'sales_name' => 'required',
            'sales_code' => 'required|unique:master_sales,sales_code,' . $this->salesId,
        ]);

        Sales::updateOrCreate(['id' => $this->salesId], [
            'sales_name' => $this->sales_name,
            'sales_code' => $this->sales_code,
            'city' => $this->city,
            'divisi' => $this->divisi,
            'status' => $this->status,
        ]);

        $this->isOpen = false;
        Cache::forget('opt_sales_city');
        Cache::forget('opt_sales_divisi');
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Sales berhasil disimpan']);
    }

    public function delete($id) {
        Sales::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Sales dihapus']);
    }

    public function closeModal() { $this->isOpen = false; }

    // --- IMPORT ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:15360']);
        $path = $this->file->store('temp-import', 'local');
        
        try {
            $stats = (new SalesImportService)->handle(Storage::disk('local')->path($path), $this->resetData);
            if(Storage::disk('local')->exists($path)) Storage::disk('local')->delete($path);
            
            Cache::forget('opt_sales_city');
            Cache::forget('opt_sales_divisi');
            $this->closeImportModal();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sukses! " . $stats['processed'] . " Sales diimport."]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // --- TARGET (HANYA OMZET) ---
    public function openTargetModal($id) {
        $sales = Sales::findOrFail($id);
        $this->salesId = $id;
        $this->selectedSalesName = $sales->sales_name;
        $this->loadTargets();
        $this->isTargetOpen = true;
    }

    public function updatedTargetYear() { $this->loadTargets(); }

    public function loadTargets() {
        $data = SalesTarget::where('sales_id', $this->salesId)
            ->where('year', $this->targetYear)
            ->get()
            ->keyBy('month');

        $this->targets = [];
        for ($m = 1; $m <= 12; $m++) {
            $this->targets[$m] = ['ims' => $data[$m]->target_ims ?? 0];
        }
    }

    public function saveTargets() {
        foreach ($this->targets as $month => $val) {
            SalesTarget::updateOrCreate(
                ['sales_id' => $this->salesId, 'year' => $this->targetYear, 'month' => $month],
                [
                    'target_ims' => $val['ims'] == '' ? 0 : $val['ims']
                ]
            );
        }
        $this->isTargetOpen = false;
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Target berhasil disimpan!']);
    }

    public function resetAllTargets() {
        SalesTarget::truncate();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Semua target berhasil di-reset.']);
    }

    public function closeTargetModal() { $this->isTargetOpen = false; }
}