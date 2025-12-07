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

    // Filter Properties
    public $search = '';
    public $startDate;
    public $endDate;
    
    public $filterCabang = '';
    public $filterSales = '';
    public $filterSupplier = '';
    public $filterStatus = '';

    public function mount()
    {
        // Default: Awal bulan ini s/d Hari ini
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    public function updated($propertyName) { $this->resetPage(); }

    public function render()
    {
        $query = Retur::query();

        // 1. Search Global
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_item', 'like', '%' . $this->search . '%')
                  ->orWhere('no_inv', 'like', '%' . $this->search . '%');
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

        // --- SUMMARY (Agregat) ---
        // Clone query agar tidak merusak pagination
        $summaryQuery = clone $query;
        $summary = $summaryQuery->toBase()
            ->selectRaw("
                COUNT(*) as total_trx,
                SUM(CAST(total_grand AS DECIMAL(20,2))) as total_nilai_retur
            ")
            ->first();

        // Ambil Data Tabel
        $returs = $query->orderBy('tgl_retur', 'desc')
                        ->orderBy('no_retur', 'desc')
                        ->paginate(10);

        // Options Cache (1 Jam)
        $optCabang   = Cache::remember('ret_cabang', 3600, fn() => Retur::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales    = Cache::remember('ret_sales', 3600, fn() => Retur::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));
        $optSupplier = Cache::remember('ret_supplier', 3600, fn() => Retur::select('supplier')->distinct()->whereNotNull('supplier')->pluck('supplier'));
        $optStatus   = Cache::remember('ret_status', 3600, fn() => Retur::select('status')->distinct()->whereNotNull('status')->pluck('status'));

        return view('livewire.laporan.rekap-retur-index', [
            'retur'       => $returs,
            'summary'     => $summary,
            'optCabang'   => $optCabang,
            'optSales'    => $optSales,
            'optSupplier' => $optSupplier,
            'optStatus'   => $optStatus,
        ])->layout('layouts.app', ['header' => 'Laporan Retur Penjualan']);
    }
}