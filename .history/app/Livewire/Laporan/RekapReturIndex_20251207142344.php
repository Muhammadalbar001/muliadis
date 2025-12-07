<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Retur;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RekapReturIndex extends Component
{
    use WithPagination;

    // Filter
    public $search = '';
    public $startDate;
    public $endDate;
    
    public $filterCabang = '';
    public $filterSales = '';
    public $filterSupplier = '';
    public $filterStatus = '';

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    public function updated($propertyName) { $this->resetPage(); }

    public function render()
    {
        $query = Retur::query();

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_item', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter Tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_retur', [$this->startDate, $this->endDate]);
        }

        // 3. Filter Dropdown
        if ($this->filterCabang) $query->where('cabang', $this->filterCabang);
        if ($this->filterSales) $query->where('sales_name', $this->filterSales);
        if ($this->filterSupplier) $query->where('supplier', $this->filterSupplier);
        if ($this->filterStatus) $query->where('status', $this->filterStatus);

        // --- SUMMARY ---
        $summaryQuery = clone $query;
        $summary = $summaryQuery->toBase()
            ->selectRaw("
                COUNT(*) as total_trx,
                SUM(CAST(total_grand AS DECIMAL(20,2))) as total_nilai_retur
            ")
            ->first();

        $data = $query->orderBy('tgl_retur', 'desc')
                      ->orderBy('no_retur', 'desc')
                      ->paginate(10);

        // Options Cache
        $optCabang   = Cache::remember('ret_cabang', 3600, fn() => Retur::select('cabang')->distinct()->pluck('cabang'));
        $optSales    = Cache::remember('ret_sales', 3600, fn() => Retur::select('sales_name')->distinct()->pluck('sales_name'));
        $optSupplier = Cache::remember('ret_supplier', 3600, fn() => Retur::select('supplier')->distinct()->pluck('supplier'));
        $optStatus   = Cache::remember('ret_status', 3600, fn() => Retur::select('status')->distinct()->pluck('status'));

        return view('livewire.laporan.rekap-retur-index', [
            'retur'       => $data,
            'summary'     => $summary,
            'optCabang'   => $optCabang,
            'optSales'    => $optSales,
            'optSupplier' => $optSupplier,
            'optStatus'   => $optStatus,
        ])->layout('layouts.app', ['header' => 'Laporan Retur Penjualan']);
    }
}