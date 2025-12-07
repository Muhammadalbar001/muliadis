<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Retur;

class RekapReturIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $query = Retur::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_retur', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
                  ->orWhere('no_inv', 'like', '%' . $this->search . '%');
            });
        }

        $data = $query->orderBy('tgl_retur', 'desc')->paginate(10);

        return view('livewire.laporan.rekap-retur-index', [
            'retur' => $data // Variabel sesuai view: @forelse ($retur as $item)
        ])->layout('layouts.app', ['header' => 'Rekap Retur Penjualan']);
    }

    public function formatNegativeParentheses($value)
    {
        return $value;
    }
}