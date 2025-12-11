<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keuangan\AccountReceivable;
use Illuminate\Support\Facades\Cache;
use Spatie\SimpleExcel\SimpleExcelWriter;

class RekapArIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCabang = [];
    public $filterSales = [];
    public $filterUmur = ''; 

    public function updatedSearch() { $this->resetPage(); }
    public function resetFilter() { $this->reset(['search', 'filterCabang', 'filterSales', 'filterUmur']); }

    public function export()
    {
        $query = AccountReceivable::query();
        $this->applyFilters($query);

        $writer = SimpleExcelWriter::streamDownload('Rekap_AR.xlsx');
        
        $query->chunk(1000, function ($items) use ($writer) {
            foreach ($items as $item) {
                $writer->addRow([
                    'Cabang'     => $item->cabang,
                    'No Invoice' => $item->no_penjualan,
                    'Pelanggan'  => $item->pelanggan_name,
                    'Tgl Faktur' => $item->tgl_penjualan,
                    'Jatuh Tempo'=> $item->jatuh_tempo,
                    'Umur (Hari)'=> $item->umur_piutang,
                    'Total'      => $item->total_nilai,
                    'Sisa'       => $item->nilai,
                    'Salesman'   => $item->sales_name,
                ]);
            }
        });

        return $writer->toBrowser();
    }

    private function applyFilters($query) {
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%'.$this->search.'%')->orWhere('pelanggan_name', 'like', '%'.$this->search.'%');
            });
        }
        if (!empty($this->filterCabang)) $query->whereIn('cabang', $this->filterCabang);
        if ($this->filterUmur == 'lancar') $query->where('umur_piutang', '<=', 30);
        if ($this->filterUmur == 'macet') $query->where('umur_piutang', '>', 30);
    }

    public function render()
    {
        $query = AccountReceivable::query();
        $this->applyFilters($query);

        // PENTING: Nama variabel $ars
        $ars = $query->orderBy('umur_piutang', 'desc')->paginate(20);

        $optCabang = Cache::remember('opt_ar_cabang', 3600, fn() => AccountReceivable::select('cabang')->distinct()->pluck('cabang'));
        $optSales = Cache::remember('opt_ar_sales', 3600, fn() => AccountReceivable::select('sales_name')->distinct()->pluck('sales_name'));

        return view('livewire.laporan.rekap-ar-index', compact('ars', 'optCabang', 'optSales'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Piutang']);
    }
}