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
    public $status = 'belum'; // Default: Tampilkan yang belum lunas

    public function updated($p) { $this->resetPage(); }

    public function render()
    {
        $query = AccountReceivable::query();

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%' . $this->search . '%')
                  ->orWhere('pelanggan_name', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filters
        if ($this->filterCabang) $query->where('cabang', $this->filterCabang);
        if ($this->filterSales) $query->where('sales_name', $this->filterSales);
        
        if ($this->status == 'belum') {
            $query->where('nilai', '>', 0);
        } elseif ($this->status == 'lunas') {
            $query->where('nilai', '<=', 0);
        }

        // --- SUMMARY ---
        $summaryQuery = clone $query;
        $summary = $summaryQuery->toBase()
            ->selectRaw("
                COUNT(*) as total_trx,
                SUM(CAST(total_nilai AS DECIMAL(20,2))) as total_awal,
                SUM(CAST(nilai AS DECIMAL(20,2))) as sisa_piutang
            ")
            ->first();

        // Data Tabel
        $data = $query->orderBy('tgl_penjualan', 'desc')
                      ->orderBy('no_penjualan', 'desc')
                      ->paginate(10);
        
        // Cache Options
        $optCabang = Cache::remember('ar_cabang', 3600, fn() => AccountReceivable::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('ar_sales', 3600, fn() => AccountReceivable::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.laporan.rekap-ar-index', [
            'ar'        => $data,
            'summary'   => $summary,
            'optCabang' => $optCabang,
            'optSales'  => $optSales
        ])->layout('layouts.app', ['header' => 'Laporan Piutang (AR)']);
    }
}