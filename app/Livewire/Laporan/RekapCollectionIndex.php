<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keuangan\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\SimpleExcel\SimpleExcelWriter;

class RekapCollectionIndex extends Component
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
        $query = Collection::query();
        $this->applyFilters($query);

        $writer = SimpleExcelWriter::streamDownload('Rekap_Collection.xlsx');
        
        $query->chunk(1000, function ($items) use ($writer) {
            foreach ($items as $item) {
                $writer->addRow([
                    'Cabang'         => $item->cabang,
                    'No Bukti'       => $item->receive_no,
                    'Status'         => $item->status,
                    'Tanggal'        => $item->tanggal,
                    'Penagih'        => $item->penagih,
                    'No Invoice'     => $item->invoice_no,
                    'Kode Pelanggan' => $item->code_customer,
                    'Nama Pelanggan' => $item->outlet_name,
                    'Salesman'       => $item->sales_name,
                    'Jumlah Bayar'   => $item->receive_amount,
                ]);
            }
        });

        return $writer->toBrowser();
    }

    private function applyFilters($query) {
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_bukti', 'like', '%'.$this->search.'%')
                  ->orWhere('receive_no', 'like', '%'.$this->search.'%')
                  ->orWhere('outlet_name', 'like', '%'.$this->search.'%');
            });
        }
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
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
        $query = Collection::query();
        $this->applyFilters($query);

        $collections = $query->orderBy('tanggal', 'desc')->paginate(20);

        // Cache Options
        $optCabang = Cache::remember('opt_col_cab', 3600, fn() => Collection::select('cabang')->distinct()->pluck('cabang'));
        $optSales  = Cache::remember('opt_col_sal', 3600, fn() => Collection::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.laporan.rekap-collection-index', compact('collections', 'optCabang', 'optSales'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Collection']);
    }
}