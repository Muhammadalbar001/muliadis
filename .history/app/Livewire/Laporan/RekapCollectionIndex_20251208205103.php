<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keuangan\Collection;
use Illuminate\Support\Facades\Cache;

class RekapCollectionIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $startDate;
    public $endDate;
    
    // Filter
    public $filterCabang = [];
    public $filterSales = [];
    public $filterPenagih = []; // Penting!

    public function updatedSearch() { $this->resetPage(); }
    public function resetFilter() { $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'filterSales', 'filterPenagih']); }

    public function render()
    {
        $query = Collection::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_bukti', 'like', '%'.$this->search.'%')
                  ->orWhere('outlet_name', 'like', '%'.$this->search.'%') // Nama Toko di tabel collections
                  ->orWhere('invoice_no', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->startDate && $this->endDate) $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        
        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);
        if (!empty($this->filterSales)) $query->whereIn('sales_name', $this->filterSales);
        if (!empty($this->filterPenagih)) $query->whereIn('penagih', $this->filterPenagih);

        $data = $query->orderBy('tanggal', 'desc')->paginate(20);

        $optCabang  = Cache::remember('opt_col_cab', 3600, fn() => Collection::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales   = Cache::remember('opt_col_sal', 3600, fn() => Collection::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));
        $optPenagih = Cache::remember('opt_col_pen', 3600, fn() => Collection::select('penagih')->distinct()->whereNotNull('penagih')->pluck('penagih'));

        return view('livewire.laporan.rekap-collection-index', compact('data', 'optCabang', 'optSales', 'optPenagih'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Collection']);
    }
}