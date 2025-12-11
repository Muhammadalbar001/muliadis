<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Transaksi\Retur;
use App\Services\Import\ReturImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ReturIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $startDate;
    public $endDate;
    public $filterCabang = [];
    public $isImportOpen = false;
    public $file;
    public $resetData = false;
    public $isDeleteDateOpen = false;
    public $deleteDateInput;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function resetFilter() { $this->reset(['search', 'startDate', 'endDate', 'filterCabang']); $this->resetPage(); }

    public function render()
    {
        $query = Retur::query();
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%');
            });
        }
        if ($this->startDate && $this->endDate) $query->whereBetween('tgl_retur', [$this->startDate, $this->endDate]);
        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);

        $summary = [
            'total_nilai'  => (clone $query)->sum('total_grand'),
            'total_faktur' => (clone $query)->distinct('no_retur')->count('no_retur'),
            'total_items'  => (clone $query)->count(),
        ];

        $returs = $query->orderBy('tgl_retur', 'desc')->paginate(50);
        $optCabang = Cache::remember('opt_cabang_retur', 3600, fn() => Retur::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));

        return view('livewire.transaksi.retur-index', compact('returs', 'optCabang', 'summary'))
            ->layout('layouts.app', ['header' => 'Retur Penjualan']);
    }

    // Import
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    public function import() {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);
        $path = $this->file->store('temp-import', 'local');
        try {
            $stats = (new ReturImportService)->handle(Storage::disk('local')->path($path), $this->resetData);
            if(Storage::disk('local')->exists($path)) Storage::disk('local')->delete($path);
            Cache::forget('opt_cabang_retur');
            $this->closeImportModal();
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sukses import " . $stats['processed'] . " data."]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Delete Date
    public function openDeleteDateModal() { $this->resetErrorBag(); $this->isDeleteDateOpen = true; }
    public function closeDeleteDateModal() { $this->isDeleteDateOpen = false; $this->deleteDateInput = null; }
    public function deleteByDate() {
        $this->validate(['deleteDateInput' => 'required|date']);
        $count = Retur::whereDate('tgl_retur', $this->deleteDateInput)->count();
        if ($count == 0) { $this->addError('deleteDateInput', 'Tidak ada data.'); return; }
        Retur::whereDate('tgl_retur', $this->deleteDateInput)->delete();
        $this->closeDeleteDateModal();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => "$count Data dihapus."]);
        Cache::forget('opt_cabang_retur');
    }

    public function delete($id) { Retur::destroy($id); $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data dihapus']); }
}