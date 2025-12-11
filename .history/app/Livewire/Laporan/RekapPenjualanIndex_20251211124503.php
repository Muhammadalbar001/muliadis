<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Penjualan;
use Illuminate\Support\Facades\Cache;

class RekapPenjualanIndex extends Component
{
    use WithPagination;

    // Filter Multi-Select
    public $search = '';
    public $startDate;
    public $endDate;
    public $filterCabang = [];
    public $filterSales = [];

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'filterSales']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Penjualan::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_item', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
        }

        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);
        if (!empty($this->filterSales)) $query->whereIn('sales_name', $this->filterSales);

        // PERBAIKAN: Gunakan nama variabel $penjualans
        $penjualans = $query->orderBy('tgl_penjualan', 'desc')->paginate(20); 

        $optCabang = Cache::remember('opt_jual_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales = Cache::remember('opt_jual_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.laporan.rekap-penjualan-index', compact('penjualans', 'optCabang', 'optSales'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Penjualan']);
    }
}