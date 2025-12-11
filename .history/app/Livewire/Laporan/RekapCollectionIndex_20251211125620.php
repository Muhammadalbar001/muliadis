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
    public $filterCabang = [];
    public $filterSales = [];

    public function updatedSearch() { $this->resetPage(); }
    public function resetFilter() { $this->reset(['search', 'startDate', 'endDate', 'filterCabang']); }

    public function export()
    {
        $query = Collection::query();
        $this->applyFilters($query);

        $writer = SimpleExcelWriter::streamDownload('Rekap_Collection.xlsx');
        
        $query->chunk(1000, function ($items) use ($writer) {
            foreach ($items as $item) {
                $writer->addRow([
                    'Cabang'     => $item->cabang,
                    'No Bukti'   => $item->receive_no,
                    'Tanggal'    => $item->tanggal,
                    'Penagih'    => $item->penagih,
                    'No Invoice' => $item->invoice_no,
                    'Pelanggan'  => $item->outlet_name,
                    'Jumlah'     => $item->receive_amount,
                    'Salesman'   => $item->sales_name,
                ]);
            }
        });

        return $writer->toBrowser();
    }

    private function applyFilters($query) {
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_bukti', 'like', '%'.$this->search.'%')->orWhere('outlet_name', 'like', '%'.$this->search.'%');
            });
        }
        if ($this->startDate && $this->endDate) $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);
    }

    public function render()
    {
        $query = Collection::query();
        $this->applyFilters($query);

        // PENTING: Nama variabel $collections
        $collections = $query->orderBy('tanggal', 'desc')->paginate(20);

        $optCabang = Cache::remember('opt_col_cab', 3600, fn() => Collection::select('cabang')->distinct()->pluck('cabang'));
        $optSales = Cache::remember('opt_col_sal', 3600, fn() => Collection::select('sales_name')->distinct()->pluck('sales_name'));
        $optPenagih = Cache::remember('opt_col_pen', 3600, fn() => Collection::select('penagih')->distinct()->pluck('penagih'));

        return view('livewire.laporan.rekap-collection-index', compact('collections', 'optCabang', 'optSales', 'optPenagih'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Collection']);
    }
}