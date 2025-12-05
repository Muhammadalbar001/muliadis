<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keuangan\AccountReceivable;

class RekapArIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $ar = AccountReceivable::query()
            ->where('no_penjualan', 'like', '%' . $this->search . '%')
            ->orWhere('pelanggan_name', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_penjualan', 'desc')
            ->paginate(10);

        return view('livewire.laporan.rekap-ar-index', [
            'ar' => $ar
        ])->layout('layouts.app', ['header' => 'Rekapitulasi Piutang (Laporan)']);
    }
}