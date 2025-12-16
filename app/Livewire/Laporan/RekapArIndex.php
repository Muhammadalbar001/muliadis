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
    
    // --- FILTER ARRAY ---
    public $filterCabang = [];
    public $filterSales = [];
    // --------------------
    
    public $filterUmur = ''; 

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    public function updatedFilterSales() { $this->resetPage(); }
    public function updatedFilterUmur() { $this->resetPage(); }

    public function resetFilter() { 
        $this->reset(['search', 'filterCabang', 'filterSales', 'filterUmur']); 
        $this->resetPage();
    }

    public function export()
    {
        $query = AccountReceivable::query();
        $this->applyFilters($query);

        $writer = SimpleExcelWriter::streamDownload('Rekap_AR.xlsx');
        
        $query->chunk(1000, function ($items) use ($writer) {
            foreach ($items as $item) {
                $writer->addRow([
                    'Cabang'         => $item->cabang,
                    'No Invoice'     => $item->no_penjualan,
                    'Kode Pelanggan' => $item->pelanggan_code,
                    'Nama Pelanggan' => $item->pelanggan_name,
                    'Salesman'       => $item->sales_name,
                    'Info'           => $item->info,
                    'Total Nilai'    => $item->total_nilai,
                    'Sisa Nilai'     => $item->nilai,
                    'Tgl Faktur'     => $item->tgl_penjualan,
                    'Tgl Antar'      => $item->tgl_antar,
                    'Jatuh Tempo'    => $item->jatuh_tempo,
                    'Current'        => $item->current,
                    '1-15 Hari'      => $item->le_15_days,
                    '16-30 Hari'     => $item->bt_16_30_days,
                    '> 30 Hari'      => $item->gt_30_days,
                    'Status'         => $item->status,
                    'Alamat'         => $item->alamat,
                    'Phone'          => $item->phone,
                    'Umur (Hari)'    => $item->umur_piutang,
                    'Unique ID'      => $item->unique_id,
                    'Range'          => $item->range_piutang
                ]);
            }
        });

        return $writer->toBrowser();
    }

    private function applyFilters($query) {
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%'.$this->search.'%')
                  ->orWhere('pelanggan_name', 'like', '%'.$this->search.'%');
            });
        }
        
        // --- FILTER MULTI SELECT ---
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }
        
        if (!empty($this->filterSales)) {
            $query->whereIn('sales_name', $this->filterSales);
        }
        
        if ($this->filterUmur == 'lancar') $query->where('umur_piutang', '<=', 30);
        if ($this->filterUmur == 'macet') $query->where('umur_piutang', '>', 30);
    }

    public function render()
    {
        $query = AccountReceivable::query();
        $this->applyFilters($query);

        $ars = $query->orderBy('umur_piutang', 'desc')->paginate(20);

        // Cache Options
        $optCabang = Cache::remember('opt_ar_cabang', 3600, fn() => AccountReceivable::select('cabang')->distinct()->pluck('cabang'));
        $optSales  = Cache::remember('opt_ar_sales', 3600, fn() => AccountReceivable::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.laporan.rekap-ar-index', compact('ars', 'optCabang', 'optSales'))
            ->layout('layouts.app', ['header' => 'Laporan Rekap Piutang']);
    }
}