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
        $query = AccountReceivable::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%' . $this->search . '%')
                  ->orWhere('pelanggan_name', 'like', '%' . $this->search . '%')
                  ->orWhere('sales_name', 'like', '%' . $this->search . '%');
            });
        }

        // Urutkan berdasarkan tanggal penjualan atau created_at
        $data = $query->orderBy('tgl_penjualan', 'desc')->paginate(10);

        return view('livewire.laporan.rekap-ar-index', [
            'ar' => $data // Variabel sesuai view: @forelse ($ar as $item)
        ])->layout('layouts.app', ['header' => 'Rekap Piutang (AR)']);
    }
}