<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Keuangan\AccountReceivable;
use App\Services\Import\ArImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ArIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterCabang = [];
    public $filterUmur = '';
    public $isImportOpen = false;
    public $file;
    public $resetData = false;
    public $isDeleteDateOpen = false;
    public $deleteDateInput;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function resetFilter() { $this->reset(['search', 'filterCabang', 'filterUmur']); $this->resetPage(); }

    public function render()
    {
        $query = AccountReceivable::query();
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%'.$this->search.'%')
                  ->orWhere('pelanggan_name', 'like', '%'.$this->search.'%');
            });
        }
        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);
        if ($this->filterUmur == 'lancar') $query->where('umur_piutang', '<=', 30);
        if ($this->filterUmur == 'macet') $query->where('umur_piutang', '>', 30);

        $summary = [
            'total_piutang' => (clone $query)->sum('nilai'),
            'total_macet'   => (clone $query)->where('umur_piutang', '>', 30)->sum('nilai'),
            'total_faktur'  => (clone $query)->count(),
        ];

        $ars = $query->orderBy('umur_piutang', 'desc')->paginate(50);
        $optCabang = Cache::remember('opt_cabang_ar', 3600, fn() => AccountReceivable::select('cabang')->distinct()->pluck('cabang'));

        return view('livewire.transaksi.ar-index', compact('ars', 'optCabang', 'summary'))
            ->layout('layouts.app', ['header' => 'Piutang (AR)']);
    }

    // Import
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    public function import() {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);
        $path = $this->file->store('temp-import', 'local');
        try {
            $stats = (new ArImportService)->handle(Storage::disk('local')->path($path), $this->resetData);
            if(Storage::disk('local')->exists($path)) Storage::disk('local')->delete($path);
            Cache::forget('opt_cabang_ar');
            $this->closeImportModal();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sukses import " . $stats['processed'] . " data."]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Delete Date (AR biasanya dihapus berdasarkan tgl import/faktur)
    public function openDeleteDateModal() { $this->resetErrorBag(); $this->isDeleteDateOpen = true; }
    public function closeDeleteDateModal() { $this->isDeleteDateOpen = false; $this->deleteDateInput = null; }
    public function deleteByDate() {
        $this->validate(['deleteDateInput' => 'required|date']);
        // Note: Pastikan kolom tanggal di DB sesuai, misal tgl_penjualan atau created_at
        $count = AccountReceivable::whereDate('tgl_penjualan', $this->deleteDateInput)->count(); 
        if ($count == 0) { $this->addError('deleteDateInput', 'Tidak ada data.'); return; }
        AccountReceivable::whereDate('tgl_penjualan', $this->deleteDateInput)->delete();
        $this->closeDeleteDateModal();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => "$count Data dihapus."]);
        Cache::forget('opt_cabang_ar');
    }

    public function delete($id) { AccountReceivable::destroy($id); $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data dihapus']); }
}