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

    // Filter Properties
    public $search = '';
    public $startDate;
    public $endDate;
    public $filterCabang = '';
    public $filterSales = '';
    public $filterDivisi = '';

    public function mount()
    {
        // Default tanggal: Awal bulan ini s/d Hari ini
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    public function updated($propertyName) 
    { 
        $this->resetPage(); 
    }

    public function render()
    {
        $query = Penjualan::query();

        // 1. Filter Pencarian Teks
        if ($this->search) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('sales_name', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter Tanggal (Penting untuk Laporan)
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
        }

        // 3. Filter Dropdown
        if ($this->filterCabang) $query->where('cabang', $this->filterCabang);
        if ($this->filterSales) $query->where('sales_name', $this->filterSales);
        if ($this->filterDivisi) $query->where('divisi', $this->filterDivisi);

        // --- HITUNG SUMMARY (Agregat) ---
        // Kita clone query agar tidak merusak pagination
        // Karena kolom di DB string, kita perlu CAST agar bisa dijumlah
        $summaryQuery = clone $query;
        $summary = $summaryQuery->toBase()
            ->selectRaw("
                COUNT(*) as total_trx,
                SUM(CAST(total_grand AS DECIMAL(20,2))) as total_omzet,
                SUM(CAST(margin AS DECIMAL(20,2))) as total_margin
            ")
            ->first();

        // Ambil Data Tabel
        $data = $query->orderBy('tgl_penjualan', 'desc')
                      ->orderBy('trans_no', 'desc')
                      ->paginate(10);

        // Options untuk Filter
        $optCabang = Cache::remember('penj_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->pluck('cabang'));
        $optSales = Cache::remember('penj_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->pluck('sales_name'));
        $optDivisi = Cache::remember('penj_divisi', 3600, fn() => Penjualan::select('divisi')->distinct()->pluck('divisi'));

        return view('livewire.laporan.rekap-penjualan-index', [
            'penjualan' => $data,
            'summary' => $summary,
            'optCabang' => $optCabang,
            'optSales' => $optSales,
            'optDivisi' => $optDivisi,
        ])->layout('layouts.app', ['header' => 'Laporan Penjualan']);
    }
    
    // Helper View untuk format angka negatif (kurung)
    public function formatUang($value)
    {
        $num = (float) $value;
        if ($num < 0) {
            return '(' . number_format(abs($num), 0, ',', '.') . ')';
        }
        return number_format($num, 0, ',', '.');
    }
}