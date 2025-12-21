<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Master\Sales;
use App\Models\Master\SalesTarget;
use App\Models\Transaksi\Penjualan;
use App\Services\Import\SalesImportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // Wajib diimport untuk fitur clear cache

class SalesIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isOpen = false;
    public $isImportOpen = false;
    public $isTargetOpen = false;
    
    // Form Properties Sales
    public $salesId, $sales_name, $sales_code, $city, $status = 'Active';

    // Form Properties Target
    public $targetYear;
    public $monthlyTargets = [];
    public $selectedSalesNameForTarget;

    // Import
    public $file, $resetData = false;

    public function mount() {
        $this->targetYear = date('Y');
        $this->resetInput();
    }

    // --- FITUR 1: AUTO DISCOVERY (SYNC DATA CERDAS) ---
    public function autoDiscover()
    {
        try {
            DB::beginTransaction();
            
            $transactions = DB::table('penjualans')
                ->select('sales_name', 'kode_sales', 'cabang')
                ->whereNotNull('sales_name')
                ->distinct()
                ->get();

            $added = 0;
            $updated = 0;

            foreach ($transactions as $trx) {
                $sales = null;
                if (!empty($trx->kode_sales)) {
                    $sales = Sales::where('sales_code', $trx->kode_sales)->first();
                }

                if (!$sales) {
                    $sales = Sales::where('sales_name', $trx->sales_name)->first();
                }

                if (!$sales) {
                    Sales::create([
                        'sales_name' => $trx->sales_name,
                        'sales_code' => $trx->kode_sales, 
                        'city'       => $trx->cabang,
                        'status'     => 'Active'
                    ]);
                    $added++;
                } else {
                    $doSave = false;
                    if (empty($sales->sales_code) && !empty($trx->kode_sales)) {
                        $sales->sales_code = $trx->kode_sales;
                        $doSave = true;
                    }
                    if (empty($sales->city) && !empty($trx->cabang)) {
                        $sales->city = $trx->cabang;
                        $doSave = true;
                    }
                    if ($doSave) {
                        $sales->save();
                        $updated++;
                    }
                }
            }

            // Update Cache setelah Sync
            $this->clearReportCaches();

            DB::commit();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sync Selesai: $added Baru, $updated Dilengkapi"]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    // --- FITUR 2: CRUD & PROPAGASI NAMA ---
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
                'status'     => $this->status,
            ]);

            // Jika Ganti Nama -> Update semua tabel REKAP/TRANSAKSI
            if ($oldName && $oldName !== $this->sales_name) {
                // 1. Update Penjualan
                Penjualan::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);
                
                // 2. Update Retur
                \App\Models\Transaksi\Retur::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);
                
                // 3. Update Account Receivable
                \App\Models\Keuangan\AccountReceivable::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);
                
                // 4. Update Collection
                \App\Models\Keuangan\Collection::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);

                // 5. PENTING: Bersihkan Cache Dropdown Laporan agar nama baru langsung muncul
                $this->clearReportCaches();
            }

            DB::commit();
            $this->isOpen = false; 
            $this->resetInput();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Salesman & Rekap Terupdate']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Fungsi Khusus untuk Menghapus Cache Dropdown di Semua Laporan
     */
    protected function clearReportCaches()
    {
        // Rekap Penjualan
        Cache::forget('opt_jual_sales');
        Cache::forget('opt_jual_cabang');
        
        // Rekap Retur
        Cache::forget('opt_ret_sales');
        Cache::forget('opt_ret_cab');
        
        // Rekap AR
        Cache::forget('opt_ar_sales');
        Cache::forget('opt_ar_cabang');
        
        // Rekap Collection
        Cache::forget('opt_col_sal');
        Cache::forget('opt_col_cab');

        // Dashboard
        Cache::forget('dash_sales');
    }

    // --- FITUR 3: MANAJEMEN TARGET ---
    public function manageTargets($id) {
        $sales = Sales::findOrFail($id);
        $this->salesId = $id;
        $this->selectedSalesNameForTarget = $sales->sales_name;
        $this->loadTargets();
        $this->isTargetOpen = true; 
    }

    public function loadTargets() {
        for ($i = 1; $i <= 12; $i++) {
            $this->monthlyTargets[$i] = 0;
        }

        $targets = SalesTarget::where('sales_id', $this->salesId)
            ->where('year', $this->targetYear)
            ->get();

        foreach ($targets as $t) {
            $this->monthlyTargets[$t->month] = (float) $t->target_ims;
        }
    }

    public function updatedTargetYear() {
        if ($this->salesId) {
            $this->loadTargets();
        }
    }

    public function saveTargets() {
        try {
            foreach ($this->monthlyTargets as $month => $amount) {
                $cleanAmount = (float) str_replace(['.', ','], '', $amount);
                
                SalesTarget::updateOrCreate(
                    [
                        'sales_id' => $this->salesId,
                        'year'     => $this->targetYear,
                        'month'    => $month
                    ],
                    [
                        'target_ims' => $cleanAmount
                    ]
                );
            }
            
            $this->isTargetOpen = false;
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Target Tahun ' . $this->targetYear . ' Berhasil Disimpan']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Simpan Target: ' . $e->getMessage()]);
        }
    }

    // --- HELPER & MODAL CONTROLS ---
    public function edit($id) {
        $s = Sales::findOrFail($id);
        $this->salesId = $id; 
        $this->sales_name = $s->sales_name; 
        $this->sales_code = $s->sales_code;
        $this->city = $s->city; 
        $this->status = $s->status; 
        $this->isOpen = true;
    }

    public function delete($id) {
        try {
            Sales::findOrFail($id)->delete();
            // Clear cache juga saat hapus agar nama hilang dari filter
            $this->clearReportCaches();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Salesman Dihapus']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Hapus: Masih ada relasi data']);
        }
    }

    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import(SalesImportService $importService) {
        $this->validate(['file' => 'required|mimes:xlsx,xls,csv|max:51200']);
        try {
            if ($this->resetData) { Sales::truncate(); }
            $importService->handle($this->file->getRealPath());
            $this->isImportOpen = false;
            $this->clearReportCaches(); // Clear cache setelah import
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Import Berhasil']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Import: ' . $e->getMessage()]);
        }
    }

    public function create() { $this->resetInput(); $this->isOpen = true; }
    
    public function closeModal() { 
        $this->isOpen = false; 
        $this->isTargetOpen = false; 
        $this->isImportOpen = false;
        $this->resetInput(); 
    }
    
    public function resetInput() { 
        $this->reset(['salesId', 'sales_name', 'sales_code', 'city', 'status', 'monthlyTargets']); 
        $this->status = 'Active'; 
    }

    public function render() {
        $query = Sales::query();
        if ($this->search) {
            $query->where(fn($q) => 
                $q->where('sales_name', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_code', 'like', '%'.$this->search.'%')
            );
        }
        
        $sales = $query->orderByRaw("CASE WHEN status = 'Active' THEN 1 ELSE 2 END")
                       ->orderBy('sales_name')
                       ->paginate(15);
                       
        return view('livewire.master.sales-index', compact('sales'))->layout('layouts.app');
    }
}