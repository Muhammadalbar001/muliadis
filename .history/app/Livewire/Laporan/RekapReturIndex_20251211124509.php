<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Retur;
use Illuminate\Support\Facades\Cache;

class RekapReturIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $startDate;
    public $endDate;
    
    public $filterCabang = [];
    public $filterSales = [];
    public $filterSupplier = [];
    public $filterDivisi = [];
    public $filterStatus = [];

    public function updatedSearch() { $this->resetPage(); }
    public function resetFilter() { 
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'filterSales', 'filterSupplier', 'filterDivisi', 'filterStatus']); 
    }

    public function render()
    {
        $query = Retur::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_item', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->startDate && $this->endDate) $query->whereBetween('tgl_retur', [$this->startDate, $this->endDate]);
        
        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);
        if (!empty($this->filterSales)) $query->whereIn('sales_name', $this->filterSales);
        if (!empty($this->filterSupplier)) $query->whereIn('supplier', $this->filterSupplier);
        if (!empty($this->filterDivisi)) $query->whereIn('divisi', $this->filterDivisi);
        if (!empty($this->filterStatus)) $query->whereIn('status', $this->filterStatus);

        // PERBAIKAN: Gunakan nama variabel $returs
        $returs = $query->orderBy('tgl_retur', 'desc')->paginate(20);

        $optCabang   = Cache::remember('opt_ret_cab', 3600, fn() => Retur::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales    = Cache::remember('opt_ret_sal', 3600, fn() => Retur::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));
        $optSupplier = Cache::remember('opt_ret_sup', 3600, fn() => Retur::select('supplier')->distinct()->whereNotNull('supplier')->pluck('supplier'));
        $optDivisi   = Cache::remember('opt_ret_div', 3600, fn() => Retur::select('divisi')->distinct()->whereNotNull('divisi')->pluck('divisi'));
        $optStatus   = Cache::remember('opt_ret_sts', 3600, fn() => Retur::select('status')->distinct()->whereNotNull('status')->pluck('status'));

        return view('livewire.laporan.rekap-retur-index', compact('returs', 'optCabang', 'optSales', 'optSupplier', 'optDivisi', 'optStatus'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Retur']);
    }
}