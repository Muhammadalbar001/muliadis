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
    public $filterCity = [];
    
    // Properti Modal CRUD (Tambah/Edit)
    public $isOpen = false;
    public $salesId, $sales_name, $sales_code, $divisi, $city, $status = 'Active';

    // Properti Modal Target
    public $isTargetOpen = false;
    public $selectedSalesId, $selectedSalesName, $targetYear;
    public $targets = [];

    // Properti Import
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    public function mount() {
        $this->targetYear = date('Y');
    }

    // --- FUNGSI CRUD (TAMBAH & EDIT) ---
    public function create() {
        $this->resetInput();
        $this->isOpen = true;
    }

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

    // Ambil data sales lama jika sedang melakukan EDIT
    $oldSalesName = null;
    if ($this->salesId) {
        $oldSalesName = Sales::where('id', $this->salesId)->value('sales_name');
    }

    // Simpan perubahan ke tabel Master Sales
    $sales = Sales::updateOrCreate(['id' => $this->salesId], [
        'sales_name' => $this->sales_name,
        'sales_code' => $this->sales_code,
        'divisi'     => $this->divisi,
        'city'       => $this->city,
        'status'     => $this->status,
    ]);

    // LOGIKA SINKRONISASI KE TABEL TRANSAKSI
    // Jika nama berubah, update semua transaksi lama dengan nama yang baru
    if ($oldSalesName && $oldSalesName !== $this->sales_name) {
        // Update di tabel Penjualan
        Penjualan::where('sales_name', $oldSalesName)
            ->update(['sales_name' => $this->sales_name]);

        // Update di tabel Retur (jika ada)
        \App\Models\Transaksi\Retur::where('sales_name', $oldSalesName)
            ->update(['sales_name' => $this->sales_name]);
            
        // Update di tabel AR/Piutang (jika ada)
        \App\Models\Keuangan\AccountReceivable::where('sales_name', $oldSalesName)
            ->update(['sales_name' => $this->sales_name]);
    }

    $this->isOpen = false;
    $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Sales dan Transaksi berhasil diperbarui']);
}

    // --- FUNGSI TARGET ---
    public function openTargetModal($id) {
        $s = Sales::findOrFail($id);
        $this->selectedSalesId = $id;
        $this->selectedSalesName = $s->sales_name;
        $this->loadTargets();
        $this->isTargetOpen = true;
    }

    public function loadTargets() {
        $existing = SalesTarget::where('sales_id', $this->selectedSalesId)
            ->where('year', $this->targetYear)
            ->get()
            ->keyBy('month');
        
        $this->targets = [];
        for ($m = 1; $m <= 12; $m++) {
            $this->targets[$m]['ims'] = $existing[$m]->target_ims ?? 0;
        }
    }

    public function saveTargets() {
        foreach ($this->targets as $month => $val) {
            SalesTarget::updateOrCreate(
                ['sales_id' => $this->selectedSalesId, 'year' => $this->targetYear, 'month' => $month],
                ['target_ims' => $val['ims'] ?? 0]
            );
        }
        $this->isTargetOpen = false;
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Target Bulanan Disimpan']);
    }

    public function closeTargetModal() {
        $this->isTargetOpen = false;
    }

    // --- FUNGSI LAINNYA (SYNC, IMPORT, DELETE) ---
    public function syncCodes() {
        try {
            $dataPenjualan = Penjualan::select('sales_name', 'kode_sales')
                ->whereNotNull('kode_sales')->where('kode_sales', '!=', '')
                ->whereNotNull('sales_name')->distinct()->get();

            $count = 0;
            foreach ($dataPenjualan as $pj) {
                $isCodeUsed = Sales::where('sales_code', $pj->kode_sales)->where('sales_name', '!=', $pj->sales_name)->exists();
                if (!$isCodeUsed) {
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

    public function import(SalesImportService $importService) {
        $this->validate(['file' => 'required|mimes:xlsx,xls,csv|max:153600']);
        try {
            if ($this->resetData) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table('sales_targets')->truncate();
                DB::table('sales')->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
            $importService->handle($this->file->getRealPath());
            $this->isImportOpen = false;
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Import Berhasil']);
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function openImportModal() { $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    public function delete($id) { Sales::destroy($id); }

    public function render() {
        $query = Sales::query();
        if ($this->search) {
            $query->where(fn($q) => $q->where('sales_name', 'like', '%'.$this->search.'%')->orWhere('sales_code', 'like', '%'.$this->search.'%'));
        }
        $sales = $query->latest()->paginate(15);
        return view('livewire.master.sales-index', compact('sales'))->layout('layouts.app');
    }
}