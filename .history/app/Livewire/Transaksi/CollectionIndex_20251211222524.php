<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Keuangan\Collection;
use App\Services\Import\CollectionImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CollectionIndex extends Component
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
        $query = Collection::query();
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_bukti', 'like', '%'.$this->search.'%')
                  ->orWhere('receive_no', 'like', '%'.$this->search.'%')
                  ->orWhere('invoice_no', 'like', '%'.$this->search.'%')
                  ->orWhere('outlet_name', 'like', '%'.$this->search.'%');
            });
        }
        if ($this->startDate && $this->endDate) $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);

        $summary = [
            'total_cair'   => (clone $query)->sum('receive_amount'),
            'total_bukti'  => (clone $query)->distinct('receive_no')->count('receive_no'),
            'total_faktur' => (clone $query)->count(),
        ];

        $collections = $query->orderBy('tanggal', 'desc')->paginate(50);
        $optCabang = Cache::remember('opt_coll_cabang', 3600, fn() => Collection::select('cabang')->distinct()->pluck('cabang'));

        return view('livewire.transaksi.collection-index', compact('collections', 'optCabang', 'summary'))
            ->layout('layouts.app', ['header' => 'Collection']);
    }

    // Import
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    public function import() {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);
        $path = $this->file->store('temp-import', 'local');
        try {
            $stats = (new CollectionImportService)->handle(Storage::disk('local')->path($path), $this->resetData);
            if(Storage::disk('local')->exists($path)) Storage::disk('local')->delete($path);
            Cache::forget('opt_coll_cabang');
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
        $count = Collection::whereDate('tanggal', $this->deleteDateInput)->count();
        if ($count == 0) { $this->addError('deleteDateInput', 'Tidak ada data.'); return; }
        Collection::whereDate('tanggal', $this->deleteDateInput)->delete();
        $this->closeDeleteDateModal();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => "$count Data dihapus."]);
        Cache::forget('opt_coll_cabang');
    }

    public function delete($id) { Collection::destroy($id); $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data dihapus']); }
}