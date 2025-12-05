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
        $retur = Retur::query()
            ->where('no_retur', 'like', '%' . $this->search . '%')
            ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orWhere('no_inv', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_retur', 'desc')
            ->paginate(10);
            
        return view('livewire.laporan.rekap-retur-index', [
            'retur' => $retur
        ])->layout('layouts.app', ['header' => 'Rekapitulasi Retur (Laporan)']);
    }

    public function formatNegativeParentheses(string $value): string
    {
        $value = trim($value);
        if (str_starts_with($value, '-')) {
            return '(' . ltrim($value, '-') . ')';
        }
        return $value;
    }
}