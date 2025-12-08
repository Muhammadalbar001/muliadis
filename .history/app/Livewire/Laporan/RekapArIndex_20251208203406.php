<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\Cache;

class RekapArIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCabang = [];
    public $filterSales = [];
    public $filterUmur = ''; // 'lancar', 'macet'

    public function updatedSearch() { $this->resetPage(); }
    public function resetFilter() { $this->reset(['search', 'filterCabang', 'filterSales', 'filterUmur']); }

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
        if (!empty($this->filterSales)) $query->whereIn('sales_name', $this->filterSales);
        
        if ($this->filterUmur == 'lancar') $query->where('umur_piutang', '<=', 30);
        if ($this->filterUmur == 'macet') $query->where('umur_piutang', '>', 30);

        $data = $query->orderBy('umur_piutang', 'desc')->paginate(20);

        $optCabang = Cache::remember('opt_ar_cabang', 3600, fn() => AccountReceivable::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales = Cache::remember('opt_ar_sales', 3600, fn() => AccountReceivable::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.laporan.rekap-ar-index', compact('data', 'optCabang', 'optSales'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Piutang']);
    }
}