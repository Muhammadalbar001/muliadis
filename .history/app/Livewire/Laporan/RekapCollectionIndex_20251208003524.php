<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keuangan\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RekapCollectionIndex extends Component
{
    use WithPagination;

    // Filter
    public $search = '';
    public $startDate;
    public $endDate;
    
    public $filterCabang = '';
    public $filterPenagih = '';

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    public function updated($propertyName) { $this->resetPage(); }

    public function render()
    {
        $query = Collection::query();

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('receive_no', 'like', '%' . $this->search . '%')
                  ->orWhere('outlet_name', 'like', '%' . $this->search . '%') // Nama Pelanggan
                  ->orWhere('invoice_no', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter Tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        // 3. Filter Dropdown
        if ($this->filterCabang) $query->where('cabang', $this->filterCabang);
        if ($this->filterPenagih) $query->where('penagih', $this->filterPenagih);

        // --- SUMMARY ---
        $summaryQuery = clone $query;
        $summary = $summaryQuery->toBase()
            ->selectRaw("
                COUNT(*) as total_trx,
                SUM(CAST(receive_amount AS DECIMAL(20,2))) as total_uang_masuk
            ")
            ->first();

        // Data Tabel
        $data = $query->orderBy('tanggal', 'desc')
                      ->orderBy('receive_no', 'desc')
                      ->paginate(10);
        
        // Options Cache
        $optCabang  = Cache::remember('coll_cabang', 3600, fn() => Collection::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optPenagih = Cache::remember('coll_penagih', 3600, fn() => Collection::select('penagih')->distinct()->whereNotNull('penagih')->pluck('penagih'));

        return view('livewire.laporan.rekap-collection-index', [
            'collection' => $data,
            'summary'    => $summary,
            'optCabang'  => $optCabang,
            'optPenagih' => $optPenagih
        ])->layout('layouts.app', ['header' => 'Laporan Collection (Pelunasan)']);
    }
}