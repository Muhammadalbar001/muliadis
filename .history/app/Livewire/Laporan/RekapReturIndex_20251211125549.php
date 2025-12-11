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
    public $filterCabang = [];
    public $filterSales = [];

    public function updatedSearch() { $this->resetPage(); }
    public function resetFilter() { $this->reset(['search', 'startDate', 'endDate', 'filterCabang']); }

    public function export()
    {
        $query = Retur::query();
        $this->applyFilters($query);

        $writer = SimpleExcelWriter::streamDownload('Rekap_Retur.xlsx');
        
        $query->chunk(1000, function ($items) use ($writer) {
            foreach ($items as $item) {
                $writer->addRow([
                    'Cabang'     => $item->cabang,
                    'No Retur'   => $item->no_retur,
                    'Tanggal'    => $item->tgl_retur,
                    'Pelanggan'  => $item->nama_pelanggan,
                    'Barang'     => $item->nama_item,
                    'Qty'        => $item->qty,
                    'Nilai'      => $item->total_grand,
                    'Salesman'   => $item->sales_name,
                ]);
            }
        });

        return $writer->toBrowser();
    }

    private function applyFilters($query) {
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%');
            });
        }
        if ($this->startDate && $this->endDate) $query->whereBetween('tgl_retur', [$this->startDate, $this->endDate]);
        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);
    }

    public function render()
    {
        $query = Retur::query();
        $this->applyFilters($query);

        // PENTING: Nama variabel $returs
        $returs = $query->orderBy('tgl_retur', 'desc')->paginate(20);

        $optCabang = Cache::remember('opt_ret_cab', 3600, fn() => Retur::select('cabang')->distinct()->pluck('cabang'));

        return view('livewire.laporan.rekap-retur-index', compact('returs', 'optCabang'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Retur']);
    }
}