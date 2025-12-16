<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Retur;
use Illuminate\Support\Facades\Cache;
use Spatie\SimpleExcel\SimpleExcelWriter;

class RekapReturIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $startDate;
    public $endDate;
    
    // --- FILTER ARRAY ---
    public $filterCabang = [];
    public $filterSales = [];
    // --------------------

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }

    public function resetFilter() {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang', 'filterSales']);
        $this->resetPage();
    }

    public function export()
    {
        $query = Retur::query();
        $this->applyFilters($query);

        $writer = SimpleExcelWriter::streamDownload('Rekap_Retur.xlsx');
        
        $query->chunk(1000, function ($items) use ($writer) {
            foreach ($items as $item) {
                $writer->addRow([
                    'Cabang'         => $item->cabang,
                    'No Retur'       => $item->no_retur,
                    'Status'         => $item->status,
                    'Tanggal'        => $item->tgl_retur,
                    'No Inv Asal'    => $item->no_inv,
                    'Pelanggan'      => $item->nama_pelanggan,
                    'Kode Item'      => $item->kode_item,
                    'Nama Item'      => $item->nama_item,
                    'Qty'            => $item->qty,
                    'Satuan'         => $item->satuan_retur,
                    'Nilai'          => $item->nilai,
                    'Total Grand'    => $item->total_grand,
                    'Salesman'       => $item->sales_name,
                    'Supplier'       => $item->supplier,
                    'Divisi'         => $item->divisi,
                ]);
            }
        });

        return $writer->toBrowser();
    }

    private function applyFilters($query) {
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_item', 'like', '%'.$this->search.'%');
            });
        }
        
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_retur', [$this->startDate, $this->endDate]);
        }
        
        // --- FILTER MULTI SELECT ---
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }
        
        if (!empty($this->filterSales)) {
            $query->whereIn('sales_name', $this->filterSales);
        }
    }

    public function render()
    {
        $query = Retur::query();
        $this->applyFilters($query);

        $returs = $query->orderBy('tgl_retur', 'desc')->paginate(20);

        // Cache Options
        $optCabang = Cache::remember('opt_ret_cab', 3600, fn() => Retur::select('cabang')->distinct()->pluck('cabang'));
        $optSales  = Cache::remember('opt_ret_sales', 3600, fn() => Retur::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.laporan.rekap-retur-index', compact('returs', 'optCabang', 'optSales'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Retur']);
    }
}