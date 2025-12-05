<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Penjualan;

class RekapPenjualanIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $penjualan = Penjualan::query()
            ->where('trans_no', 'like', '%' . $this->search . '%')
            ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_penjualan', 'desc')
            ->paginate(10);

        return view('livewire.laporan.rekap-penjualan-index', [
            'penjualan' => $penjualan
        ])->layout('layouts.app', ['header' => 'Rekapitulasi Penjualan (Laporan Lengkap)']);
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