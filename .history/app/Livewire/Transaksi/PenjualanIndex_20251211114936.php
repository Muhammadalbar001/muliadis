<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Transaksi\Penjualan;
use App\Services\Import\PenjualanImportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // WAJIB: Untuk grouping query

class PenjualanIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $startDate;
    public $endDate;
    public $filterCabang = []; 

    // MODAL IMPORT
    public $isImportOpen = false;
    public $file;
    public $resetData = false;

    // MODAL DETAIL (NEW)
    public $isDetailOpen = false;
    public $detailItems = [];
    public $selectedFaktur;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }
    
    public function resetFilter()
    {
        $this->reset(['search', 'startDate', 'endDate', 'filterCabang']);
        $this->resetPage();
    }

    // --- LOGIC DETAIL FAKTUR ---
    public function openDetail($trans_no)
    {
        $this->selectedFaktur = $trans_no;
        // Ambil rincian item berdasarkan nomor faktur
        $this->detailItems = Penjualan::where('trans_no', $trans_no)->get();
        $this->isDetailOpen = true;
    }

    public function closeDetail()
    {
        $this->isDetailOpen = false;
        $this->detailItems = [];
    }

    public function render()
    {
        $query = Penjualan::query();

        // 1. Filter Pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('trans_no', 'like', '%'.$this->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$this->search.'%')
                  ->orWhere('sales_name', 'like', '%'.$this->search.'%');
            });
        }

        // 2. Filter Tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_penjualan', [$this->startDate, $this->endDate]);
        }

        // 3. Filter Cabang
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        // --- GROUPING LOGIC (HEADER) ---
        // Kita hanya mengambil kolom-kolom utama untuk ditampilkan di tabel luar
        // Menggunakan aggregate function SUM dan COUNT
        $penjualans = $query
            ->select(
                'trans_no',
                'tgl_penjualan',
                'nama_pelanggan',
                'sales_name',
                'cabang',
                DB::raw('MIN(id) as id'), // Ambil satu ID sembarang untuk key
                DB::raw('SUM(total_grand) as total_invoice'), // Total Faktur
                DB::raw('COUNT(*) as total_items') // Jumlah Item
            )
            ->groupBy('trans_no', 'tgl_penjualan', 'nama_pelanggan', 'sales_name', 'cabang')
            ->orderBy('tgl_penjualan', 'desc')
            ->paginate(10);

        $optCabang = Cache::remember('opt_cabang_jual', 3600, fn() => 
            Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang')
        );

        return view('livewire.transaksi.penjualan-index', compact('penjualans', 'optCabang'))
            ->layout('layouts.app', ['header' => 'Transaksi Penjualan']);
    }

    // --- IMPORT LOGIC ---
    public function openImportModal() { $this->resetErrorBag(); $this->isImportOpen = true; }
    public function closeImportModal() { $this->isImportOpen = false; $this->file = null; }
    
    public function import() 
    {
        $this->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:153600']);

        // LOCKING: Mencegah Double Import
        $lock = Cache::lock('importing_penjualan', 600);

        if (!$lock->get()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Sedang ada proses import berjalan. Mohon tunggu!']);
            return;
        }

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            $path = $this->file->store('temp-import', 'local');
            $fullPath = Storage::disk('local')->path($path);

            // Import Service
            $stats = (new PenjualanImportService)->handle($fullPath, $this->resetData); 
            $count = $stats['processed'] ?? 0;
            $skipped = $stats['skipped_empty'] ?? 0;

            if(Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            Cache::forget('opt_cabang_jual');
            $this->closeImportModal();
            
            $this->dispatch('show-toast', [
                'type' => 'success', 
                'message' => "BERHASIL! Masuk: " . number_format($count) . " baris. (Skip: $skipped)"
            ]);

        } catch (\Exception $e) {
            if(isset($path) && Storage::disk('local')->exists($path)) { Storage::disk('local')->delete($path); }
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        } finally {
            $lock->release();
        }
    }

    // --- DELETE LOGIC (UPDATED) ---
    // Hapus berdasarkan Trans No (Faktur), bukan ID tunggal
    public function delete($trans_no) {
        Penjualan::where('trans_no', $trans_no)->delete();
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Faktur penjualan berhasil dihapus']);
    }
}   