<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RekapArIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCabang = '';
    public $filterSales = '';
    public $status = 'all'; // all, lunas, belum

    public function updatedSearch() { $this->resetPage(); }

    public function render()
    {
        $query = AccountReceivable::query();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%' . $this->search . '%')
                  ->orWhere('pelanggan_name', 'like', '%' . $this->search . '%');
            });
        }

        // Filters
        if ($this->filterCabang) $query->where('cabang', $this->filterCabang);
        if ($this->filterSales) $query->where('sales_name', $this->filterSales);
        
        if ($this->status == 'belum') {
            // Sisa piutang > 0 atau status != Lunas
            $query->where('nilai', '>', 0);
        } elseif ($this->status == 'lunas') {
            $query->where('nilai', '<=', 0);
        }

        // Summary (Wajib Cast karena nilai string)
        $summaryQuery = clone $query;
        $summary = $summaryQuery->toBase()
            ->selectRaw("
                SUM(CAST(total_nilai AS DECIMAL(20,2))) as total_tagihan,
                SUM(CAST(nilai AS DECIMAL(20,2))) as sisa_piutang
            ")->first();

        $ar = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $optCabang = Cache::remember('ar_cabang', 3600, fn() => AccountReceivable::select('cabang')->distinct()->pluck('cabang'));
        $optSales = Cache::remember('ar_sales', 3600, fn() => AccountReceivable::select('sales_name')->distinct()->pluck('sales_name'));

        return view('livewire.laporan.rekap-ar-index', [
            'ar' => $ar,
            'summary' => $summary,
            'optCabang' => $optCabang,
            'optSales' => $optSales
        ])->layout('layouts.app', ['header' => 'Laporan Piutang (AR)']);
    }
}