<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi\Penjualan;
use Carbon\Carbon;

class RekapPenjualanIndex extends Component
{
    use WithPagination;

    // Filter
    public $search = '';
    public $startDate;
    public $endDate;

    public function mount()
    {
        // Default: Periode bulan ini
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate() { $this->resetPage(); }

    public function render()
    {
        $query = Penjualan::query()
            ->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate])
            ->where(function($q) {
                $q->where('trans_no', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
                  ->orWhere('sales_name', 'like', '%' . $this->search . '%');
            });

        // Hitung Summary (Sebelum paginate)
        // Kita gunakan 'total_grand' (pastikan tipe data di DB numeric atau di-cast)
        // Karena di migrasi Anda pakai string, kita perlu hati-hati. 
        // Namun untuk sum di SQL biasanya aman jika string berisi angka murni.
        $totalOmzet = $query->sum('total_grand'); 
        $totalTransaksi = $query->count();

        $data = $query->orderBy('tgl_penjualan', 'desc')->paginate(10);

        return view('livewire.laporan.rekap-penjualan-index', [
            'penjualans' => $data,
            'totalOmzet' => $totalOmzet,
            'totalTransaksi' => $totalTransaksi
        ])->layout('layouts.app', ['header' => 'Laporan Penjualan']);
    }
}