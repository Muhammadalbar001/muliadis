<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keuangan\Collection;

class RekapCollectionIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $collections = Collection::query()
            ->where('receive_no', 'like', '%' . $this->search . '%')
            ->orWhere('outlet_name', 'like', '%' . $this->search . '%')
            ->orWhere('invoice_no', 'like', '%' . $this->search . '%')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('livewire.laporan.rekap-collection-index', [
            'collections' => $collections
        ])->layout('layouts.app', ['header' => 'Rekapitulasi Collection (Laporan)']);
    }
}