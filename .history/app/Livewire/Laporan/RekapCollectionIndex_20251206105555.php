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
        $query = Collection::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('receive_no', 'like', '%' . $this->search . '%')
                  ->orWhere('invoice_no', 'like', '%' . $this->search . '%')
                  ->orWhere('outlet_name', 'like', '%' . $this->search . '%');
            });
        }

        $data = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('livewire.laporan.rekap-collection-index', [
            'collections' => $data // Variabel sesuai view: @forelse ($collections as $item)
        ])->layout('layouts.app', ['header' => 'Rekap Collection']);
    }
}