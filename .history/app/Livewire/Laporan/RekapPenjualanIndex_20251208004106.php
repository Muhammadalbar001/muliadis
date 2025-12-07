<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Penjualan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RekapPenjualanIndex extends Component
{
    use WithPagination;

    // Filter
    public $search = '';
    public $startDate;
    public $endDate;
    
    public $filterCabang = '';
    public $filterSales = '';
    public $filterDivisi = '';

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    public function updated($propertyName) { $this->resetPage(); }

    public function render()
    {
        $query = Penjualan::query();

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_item', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter Tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
        }

        // 3. Dropdown Filters
        if ($this->filterCabang) $query->where('cabang', $this->filterCabang);
        if ($this->filterSales) $query->where('sales_name', $this->filterSales);
        if ($this->filterDivisi) $query->where('divisi', $this->filterDivisi);

        // --- SUMMARY (Hitung Cepat) ---
        // Clone query agar tidak mengganggu pagination
        $summaryQuery = clone $query;
        $summary = $summaryQuery->toBase()
            ->selectRaw("
                COUNT(*) as total_trx,
                SUM(CAST(total_grand AS DECIMAL(20,2))) as total_omzet,
                SUM(CAST(margin AS DECIMAL(20,2))) as total_margin
            ")
            ->first();

        // Data Tabel
        $data = $query->orderBy('tgl_penjualan', 'desc')
                      ->orderBy('trans_no', 'desc')
                      ->paginate(10);

        // Options Cache (1 Jam)
        $optCabang = Cache::remember('penj_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('penj_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));
        $optDivisi = Cache::remember('penj_divisi', 3600, fn() => Penjualan::select('divisi')->distinct()->whereNotNull('divisi')->pluck('divisi'));

        return view('livewire.laporan.rekap-penjualan-index', [
            'penjualan' => $data,
            'summary'   => $summary,
            'optCabang' => $optCabang,
            'optSales'  => $optSales,
            'optDivisi' => $optDivisi,
        ])->layout('layouts.app', ['header' => 'Laporan Penjualan']);
    }
    
    // Helper format angka negatif kurung (misal: (5000))
    public function formatUang($value)
    {
        $num = (float) $value;
        if ($num < 0) {
            return '(' . number_format(abs($num), 0, ',', '.') . ')';
        }
        return number_format($num, 0, ',', '.');
    }
}