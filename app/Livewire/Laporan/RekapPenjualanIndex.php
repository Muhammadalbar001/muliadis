<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Penjualan;
use Illuminate\Support\Facades\Cache;
use Spatie\SimpleExcel\SimpleExcelWriter;

class RekapPenjualanIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $startDate;
    public $endDate;
    
    // --- FILTER ARRAY (MULTI SELECT) ---
    public $filterCabang = [];
    public $filterSales = [];
    // -----------------------------------

    // Reset pagination saat filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }

    public function resetFilter() {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'filterSales']);
        $this->resetPage();
    }

    public function export()
    {
        $query = Penjualan::query();
        $this->applyFilters($query);

        $writer = SimpleExcelWriter::streamDownload('Rekap_Penjualan.xlsx');
        
        // Chunking untuk performa data besar
        $query->chunk(1000, function ($items) use ($writer) {
            foreach ($items as $item) {
                $writer->addRow([
                    'Cabang'       => $item->cabang,
                    'No Faktur'    => $item->trans_no,
                    'Tanggal'      => $item->tgl_penjualan,
                    'Pelanggan'    => $item->nama_pelanggan,
                    'Kode Item'    => $item->kode_item,
                    'Nama Barang'  => $item->nama_item,
                    'Qty'          => $item->qty,
                    'Satuan'       => $item->satuan_jual,
                    'Harga'        => $item->nilai_jual_net,
                    'Diskon'       => $item->total_diskon,
                    'Total'        => $item->total_grand,
                    'Salesman'     => $item->sales_name,
                ]);
            }
        });

        return $writer->toBrowser();
    }

    private function applyFilters($query)
    {
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
        
        // --- FILTER MULTI SELECT (WhereIn) ---
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }
        
        if (!empty($this->filterSales)) {
            $query->whereIn('sales_name', $this->filterSales);
        }
    }

    public function render()
    {
        $query = Penjualan::query();
        $this->applyFilters($query);

        $penjualans = $query->orderBy('tgl_penjualan', 'desc')->paginate(20); 

        // Cache Options untuk performa
        $optCabang = Cache::remember('opt_jual_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->pluck('cabang'));
        $optSales = Cache::remember('opt_jual_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->orderBy('sales_name')->pluck('sales_name'));

        return view('livewire.laporan.rekap-penjualan-index', compact('penjualans', 'optCabang', 'optSales'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Penjualan']);
    }
}