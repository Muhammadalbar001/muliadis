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

    public function updatedSearch() { $this->resetPage(); }
    public function resetFilter() { $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'filterSales']); }

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

        $data = $query->orderBy('tgl_retur', 'desc')->paginate(20);

        $optCabang = Cache::remember('opt_retur_cabang', 3600, fn() => Retur::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales = Cache::remember('opt_retur_sales', 3600, fn() => Retur::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.laporan.rekap-retur-index', compact('data', 'optCabang', 'optSales'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Retur']);
    }
}