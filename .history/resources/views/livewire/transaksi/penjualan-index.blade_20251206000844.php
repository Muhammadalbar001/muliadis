<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Transaksi\Penjualan;
use App\Services\Import\PenjualanImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PenjualanIndex extends Component
{
    use WithFileUploads, WithPagination;

    // --- Properties Tampilan ---
    public $search = '';
    public $isImportOpen = false; // Modal Import
    public $isDetailOpen = false; // Modal Detail (Opsional, untuk melihat rincian)
    
    // --- Properties Import ---
    public $file;
    public $iteration = 1;

    // --- Properties Detail (Jika ingin fitur lihat detail) ---
    public $selectedItem;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Sesuaikan query dengan kolom database Anda
        $penjualans = Penjualan::query()
            ->where('trans_no', 'like', '%' . $this->search . '%')
            ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orWhere('sales_name', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_penjualan', 'desc')
            ->paginate(10);

        return view('livewire.transaksi.penjualan-index', [
            'penjualans' => $penjualans
        ])->layout('layouts.app', ['header' => 'Data Penjualan']);
    }

    // ==========================================
    // LOGIKA IMPORT EXCEL
    // ==========================================

    public function openImportModal()
    {
        $this->resetErrorBag();
        $this->isImportOpen = true;
    }

    public function closeImportModal()
    {
        $this->isImportOpen = false;
        $this->file = null;
        $this->iteration++;
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:102400', // Max 100MB
        ]);

        $path = null;

        try {
            // 1. Simpan File Sementara
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) {
                throw new \Exception("File gagal disimpan di server.");
            }

            // 2. Proses Service Import
            // Pastikan Service ini sudah dioptimasi (menggunakan Batch Insert/Upsert)
            $importService = new PenjualanImportService();
            $stats = $importService->handle($fullPath); 
            // Asumsi return $stats = ['total_rows' => 100, 'imported' => 90, ...];

            // 3. Hapus File
            if (Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            // 4. Feedback
            $msg = "Import Selesai! Total: {$stats['total_rows']}, Sukses: {$stats['imported']}";
            if ($stats['skipped_error'] > 0) {
                $msg .= ", Error: {$stats['skipped_error']}";
            }

            $this->closeImportModal();
            session()->flash('success', $msg);

        } catch (\Exception $e) {
            // Cleanup jika error
            if (isset($filename) && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }

            Log::error('Import Penjualan Gagal: ' . $e->getMessage());
            $this->addError('file', 'Gagal: ' . $e->getMessage());
        }
    }

    // ==========================================
    // LOGIKA DETAIL / DELETE
    // ==========================================

    public function delete($id)
    {
        try {
            Penjualan::destroy($id);
            session()->flash('success', 'Data penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}