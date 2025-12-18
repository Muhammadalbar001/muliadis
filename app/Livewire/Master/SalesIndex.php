<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Sales;
use App\Models\Transaksi\Penjualan;
use App\Models\Master\SalesTarget;
use Illuminate\Support\Facades\DB;

class SalesIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCity = [];
    public $filterDivisi = [];
    
    // Form Properties
    public $isOpen = false;
    public $salesId, $sales_name, $sales_code, $divisi, $city, $status = 'Active';

    // Target Properties
    public $isTargetOpen = false;
    public $selectedSalesId, $selectedSalesName, $targetYear;
    public $targets = [];

    protected $listeners = ['refreshSales' => '$refresh'];

    public function mount() {
        $this->targetYear = date('Y');
    }

    // --- FITUR SYNC KODE SALES ---
    public function syncCodes()
    {
        // 1. Ambil Nama & Kode unik dari database Penjualan
        $dataPenjualan = Penjualan::select('sales_name', 'kode_sales')
            ->whereNotNull('kode_sales')
            ->where('kode_sales', '!=', '')
            ->distinct()
            ->get();

        $count = 0;
        foreach ($dataPenjualan as $pj) {
            // 2. Update tabel sales jika namanya cocok tapi kodenya masih kosong
            $updated = Sales::where('sales_name', $pj->sales_name)
                ->where(function($q) {
                    $q->whereNull('sales_code')->orWhere('sales_code', '');
                })
                ->update(['sales_code' => $pj->kode_sales]);
            
            if($updated) $count++;
        }

        $this->dispatch('show-toast', ['type' => 'success', 'message' => "$count Kode Sales berhasil disinkronkan!"]);
    }

    public function render()
    {
        $query = Sales::query();

        if ($this->search) {
            $query->where('sales_name', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_code', 'like', '%'.$this->search.'%');
        }

        if (!empty($this->filterCity)) {
            $query->whereIn('city', $this->filterCity);
        }

        if (!empty($this->filterDivisi)) {
            $query->whereIn('divisi', $this->filterDivisi);
        }

        $sales = $query->latest()->paginate(15);
        
        $optCity = Sales::select('city')->distinct()->whereNotNull('city')->pluck('city');
        $optDivisi = Sales::select('divisi')->distinct()->whereNotNull('divisi')->pluck('divisi');

        return view('livewire.master.sales-index', compact('sales', 'optCity', 'optDivisi'))
            ->layout('layouts.app', ['header' => 'Master Salesman']);
    }

    // ... (Fungsi CRUD & Target lainnya tetap sama) ...
    public function create() { $this->resetInput(); $this->isOpen = true; }
    
    public function edit($id) {
        $s = Sales::findOrFail($id);
        $this->salesId = $id;
        $this->sales_name = $s->sales_name;
        $this->sales_code = $s->sales_code;
        $this->divisi = $s->divisi;
        $this->city = $s->city;
        $this->status = $s->status;
        $this->isOpen = true;
    }

    public function store() {
        $this->validate([
            'sales_name' => 'required',
            'sales_code' => 'nullable|unique:sales,sales_code,'.$this->salesId,
        ]);

        Sales::updateOrCreate(['id' => $this->salesId], [
            'sales_name' => $this->sales_name,
            'sales_code' => $this->sales_code,
            'divisi' => $this->divisi,
            'city' => $this->city,
            'status' => $this->status,
        ]);

        $this->closeModal();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Berhasil Disimpan']);
    }

    public function closeModal() { $this->isOpen = false; $this->resetInput(); }
    private function resetInput() { $this->salesId = null; $this->sales_name = ''; $this->sales_code = ''; $this->divisi = ''; $this->city = ''; $this->status = 'Active'; }

    public function delete($id) { Sales::destroy($id); }
}