<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Master\Sales;
use App\Models\Transaksi\Penjualan;
use App\Models\Master\SalesTarget;
use App\Services\Import\SalesImportService;
use Illuminate\Support\Facades\DB;

class SalesIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isOpen = false;
    public $isImportOpen = false; // Pastikan ini ada
    
    // Form Properties
    public $salesId, $sales_name, $sales_code, $divisi, $city, $status = 'Active';

    // Target & Import Properties
    public $selectedSalesId, $selectedSalesName, $targetYear, $targets = [];
    public $file, $resetData = false;

    public function mount() { 
        $this->targetYear = date('Y'); 
    }

    // --- FUNGSI MODAL IMPORT ---
    public function openImportModal() 
    { 
        $this->resetErrorBag();
        $this->isImportOpen = true; 
    }

    public function closeImportModal() 
    { 
        $this->isImportOpen = false; 
        $this->file = null; 
    }

    public function import(SalesImportService $importService) 
    {
        $this->validate(['file' => 'required|mimes:xlsx,xls,csv|max:51200']);
        
        try {
            if ($this->resetData) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table('sales_targets')->truncate();
                DB::table('sales')->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            $importService->handle($this->file->getRealPath());
            
            $this->isImportOpen = false;
            $this->file = null;
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Import Salesman Berhasil']);
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    // --- FUNGSI SYNC & CRUD ---
    public function syncCodes() {
        try {
            $dataPenjualan = DB::table('penjualans')
                ->select('sales_name', 'kode_sales')
                ->whereNotNull('kode_sales')->where('kode_sales', '!=', '')
                ->whereNotNull('sales_name')->distinct()->get();

            $count = 0;
            foreach ($dataPenjualan as $pj) {
                $isUsed = Sales::where('sales_code', $pj->kode_sales)->where('sales_name', '!=', $pj->sales_name)->exists();
                if (!$isUsed) {
                    $updated = Sales::where('sales_name', $pj->sales_name)
                        ->where(fn($q) => $q->whereNull('sales_code')->orWhere('sales_code', ''))
                        ->update(['sales_code' => $pj->kode_sales]);
                    if($updated) $count++;
                }
            }
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "$count Kode diperbarui"]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function store() {
        $this->validate([
            'sales_name' => 'required|min:3',
            'sales_code' => 'nullable|unique:sales,sales_code,' . $this->salesId,
        ]);

        try {
            DB::beginTransaction();
            $oldName = $this->salesId ? Sales::where('id', $this->salesId)->value('sales_name') : null;
            
            $sales = Sales::updateOrCreate(['id' => $this->salesId], [
                'sales_name' => $this->sales_name,
                'sales_code' => $this->sales_code ?: null,
                'city'       => $this->city,
                'status'     => $this->status ?? 'Active',
            ]);

            if ($oldName && $oldName !== $this->sales_name) {
                Penjualan::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);
                \App\Models\Transaksi\Retur::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);
                \App\Models\Keuangan\AccountReceivable::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);
            }

            DB::commit();
            $this->isOpen = false;
            $this->resetInput();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Tersimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function resetInput() { $this->reset(['salesId', 'sales_name', 'sales_code', 'divisi', 'city', 'status']); }
    public function closeModal() { $this->isOpen = false; $this->resetInput(); }
    public function create() { $this->resetInput(); $this->isOpen = true; }
    public function edit($id) {
        $s = Sales::findOrFail($id);
        $this->salesId = $id; $this->sales_name = $s->sales_name; $this->sales_code = $s->sales_code;
        $this->city = $s->city; $this->status = $s->status; $this->isOpen = true;
    }

    public function render() {
        $query = Sales::query();
        if ($this->search) {
            $query->where(fn($q) => $q->where('sales_name', 'like', '%'.$this->search.'%')->orWhere('sales_code', 'like', '%'.$this->search.'%'));
        }
        $sales = $query->latest()->paginate(15);
        return view('livewire.master.sales-index', compact('sales'))->layout('layouts.app');
    }
}