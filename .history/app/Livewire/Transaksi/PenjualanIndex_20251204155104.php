<?php

namespace App\Livewire\Transaksi;

// ... imports ...

class PenjualanIndex extends Component
{
    // ... properties and methods ...

    public function render()
    {
        $penjualan = Penjualan::query()
            ->where('trans_no', 'like', '%' . $this->search . '%')
            ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_penjualan', 'desc')
            ->paginate(10);
            
        // Logika penentuan header
        $currentRoute = request()->route()->getName();
        $header = ($currentRoute === 'laporan.rekap_penjualan') 
                  ? 'Rekapitulasi Penjualan (Laporan)' 
                  : 'Order Penjualan (Input Transaksi)';

        return view('livewire.transaksi.penjualan-index', [
            'penjualan' => $penjualan
        ])->layout('layouts.app', ['header' => $header]);
    }
    
    // ... import method ...
}