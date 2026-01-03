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
use Illuminate\Support\Facades\Cache;

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
    public $bulkTargetValue = ''; // Properti baru untuk input massal

    // Import
    public $file, $resetData = false;

    public function mount() {
        $this->targetYear = date('Y');
        $this->resetInput();
    }

    /**
     * FITUR: PARSE SHORTCUT (100jt -> 100000000)
     */
    private function parseShortcutValue($value)
    {
        // Bersihkan spasi dan ubah ke lowercase
        $value = strtolower(str_replace([' ', ','], ['', '.'], $value));
        if (empty($value)) return 0;

        $multipliers = [
            'jt' => 1000000,
            'j'  => 1000000,
            'm'  => 1000000000,
            'rb' => 1000,
            'r'  => 1000,
            'k'  => 1000,
        ];

        foreach ($multipliers as $suffix => $multiplier) {
            if (str_ends_with($value, $suffix)) {
                $num = substr($value, 0, -strlen($suffix));
                // Pastikan bagian depan adalah angka valid
                if (is_numeric($num)) {
                    return (float)$num * $multiplier;
                }
            }
        }

        return is_numeric($value) ? (float)$value : 0;
    }

    /**
     * FITUR: Terapkan satu nilai ke semua bulan
     */
    public function applyBulkTarget()
    {
        $numericValue = $this->parseShortcutValue($this->bulkTargetValue);
        
        if ($numericValue <= 0 && !empty($this->bulkTargetValue)) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Format angka tidak valid']);
            return;
        }

        for ($i = 1; $i <= 12; $i++) {
            $this->monthlyTargets[$i] = $numericValue;
        }
        
        $this->bulkTargetValue = ''; // Reset field setelah apply
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Target diterapkan ke 12 bulan']);
    }

    // --- FITUR AUTO DISCOVERY ---
    public function autoDiscover()
    {
        try {
            DB::beginTransaction();
            $transactions = DB::table('penjualans')->select('sales_name', 'kode_sales', 'cabang')->whereNotNull('sales_name')->distinct()->get();
            $added = 0; $updated = 0;

            foreach ($transactions as $trx) {
                $sales = !empty($trx->kode_sales) ? Sales::where('sales_code', $trx->kode_sales)->first() : null;
                if (!$sales) $sales = Sales::where('sales_name', $trx->sales_name)->first();

                if (!$sales) {
                    Sales::create(['sales_name' => $trx->sales_name, 'sales_code' => $trx->kode_sales, 'city' => $trx->cabang, 'status' => 'Active']);
                    $added++;
                } else {
                    $doSave = false;
                    if (empty($sales->sales_code) && !empty($trx->kode_sales)) { $sales->sales_code = $trx->kode_sales; $doSave = true; }
                    if (empty($sales->city) && !empty($trx->cabang)) { $sales->city = $trx->cabang; $doSave = true; }
                    if ($doSave) { $sales->save(); $updated++; }
                }
            }
            $this->clearReportCaches();
            DB::commit();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sync Selesai: $added Baru, $updated Dilengkapi"]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function store() {
        $this->validate(['sales_name' => 'required|min:3', 'sales_code' => 'nullable|unique:sales,sales_code,' . $this->salesId]);
        try {
            DB::beginTransaction();
            $oldName = $this->salesId ? Sales::where('id', $this->salesId)->value('sales_name') : null;
            $sales = Sales::updateOrCreate(['id' => $this->salesId], ['sales_name' => $this->sales_name, 'sales_code' => $this->sales_code ?: null, 'city' => $this->city, 'status' => $this->status]);

            if ($oldName && $oldName !== $this->sales_name) {
                Penjualan::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);
                \App\Models\Transaksi\Retur::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);
                \App\Models\Keuangan\AccountReceivable::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);
                \App\Models\Keuangan\Collection::where('sales_name', $oldName)->update(['sales_name' => $this->sales_name]);
                $this->clearReportCaches();
            }
            DB::commit();
            $this->isOpen = false; $this->resetInput();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Salesman Terupdate']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    protected function clearReportCaches() {
        Cache::forget('opt_jual_sales'); Cache::forget('opt_jual_cabang');
        Cache::forget('opt_ret_sales'); Cache::forget('opt_ret_cab');
        Cache::forget('opt_ar_sales'); Cache::forget('opt_ar_cabang');
        Cache::forget('opt_col_sal'); Cache::forget('opt_col_cab');
        Cache::forget('dash_sales');
    }

    public function manageTargets($id) {
        $sales = Sales::findOrFail($id);
        $this->salesId = $id;
        $this->selectedSalesNameForTarget = $sales->sales_name;
        $this->bulkTargetValue = ''; // Reset bulk input
        $this->loadTargets();
        $this->isTargetOpen = true; 
    }

    public function loadTargets() {
        for ($i = 1; $i <= 12; $i++) { $this->monthlyTargets[$i] = 0; }
        $targets = SalesTarget::where('sales_id', $this->salesId)->where('year', $this->targetYear)->get();
        foreach ($targets as $t) { $this->monthlyTargets[$t->month] = (float) $t->target_ims; }
    }

    public function updatedTargetYear() { if ($this->salesId) $this->loadTargets(); }

    public function saveTargets() {
        try {
            foreach ($this->monthlyTargets as $month => $amount) {
                $cleanAmount = is_string($amount) ? (float) str_replace(['.', ','], '', $amount) : (float) $amount;
                SalesTarget::updateOrCreate(
                    ['sales_id' => $this->salesId, 'year' => $this->targetYear, 'month' => $month],
                    ['target_ims' => $cleanAmount]
                );
            }
            $this->isTargetOpen = false;
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Target Tahun ' . $this->targetYear . ' Berhasil Disimpan']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Simpan Target: ' . $e->getMessage()]);
        }
    }

    public function edit($id) {
        $s = Sales::findOrFail($id);
        $this->salesId = $id; $this->sales_name = $s->sales_name; $this->sales_code = $s->sales_code;
        $this->city = $s->city; $this->status = $s->status; $this->isOpen = true;
    }

    public function delete($id) {
        try {
            Sales::findOrFail($id)->delete();
            $this->clearReportCaches();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Salesman Dihapus']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Hapus: Masih ada relasi data']);
        }
    }

    public function create() { $this->resetInput(); $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; $this->isTargetOpen = false; $this->isImportOpen = false; $this->resetInput(); }
    public function resetInput() { $this->reset(['salesId', 'sales_name', 'sales_code', 'city', 'status', 'monthlyTargets', 'bulkTargetValue']); $this->status = 'Active'; }

    public function render() {
        $query = Sales::query();
        if ($this->search) {
            $query->where(fn($q) => $q->where('sales_name', 'like', '%'.$this->search.'%')->orWhere('sales_code', 'like', '%'.$this->search.'%'));
        }
        $sales = $query->orderByRaw("CASE WHEN status = 'Active' THEN 1 ELSE 2 END")->orderBy('sales_name')->paginate(15);
        return view('livewire.master.sales-index', compact('sales'))->layout('layouts.app');
    }
}