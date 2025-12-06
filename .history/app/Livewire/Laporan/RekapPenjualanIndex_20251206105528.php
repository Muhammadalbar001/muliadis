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
        $query = Penjualan::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('sales_name', 'like', '%' . $this->search . '%');
            });
        }

        // Menggunakan paginate agar halaman tidak berat memuat 51 kolom sekaligus
        $data = $query->orderBy('tgl_penjualan', 'desc')->paginate(10);

        return view('livewire.laporan.rekap-penjualan-index', [
            'penjualan' => $data // Variabel ini sesuai dengan @forelse di view Anda
        ])->layout('layouts.app', ['header' => 'Rekap Penjualan (51 Kolom)']);
    }
    
    // Fitur format angka negatif (opsional, untuk helper di view)
    public function formatNegativeParentheses($value)
    {
        // Logic ini sudah ada di javascript view, tapi bisa juga di handle di sini jika perlu
        return $value; 
    }
}