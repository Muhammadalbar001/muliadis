<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Transaksi\Retur;
use App\Services\Import\ReturImportService; // Asumsi service ini ada
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReturIndex extends Component
{
    use WithFileUploads, WithPagination;

    // --- Properties Tampilan ---
    public $search = '';
    public $isImportOpen = false;
    
    // --- Properties Import ---
    public $file;
    public $iteration = 1;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query menyesuaikan tabel 'returs'
        $returs = Retur::query()
            ->where('no_retur', 'like', '%' . $this->search . '%')
            ->orWhere('no_inv', 'like', '%' . $this->search . '%') // Penting: Bisa cari berdasarkan Invoice aslinya
            ->orWhere('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_retur', 'desc')
            ->paginate(10);

        return view('livewire.transaksi.retur-index', [
            'returs' => $returs
        ])->layout('layouts.app', ['header' => 'Data Retur Penjualan']);
    }

    // ==========================================
    // LOGIKA IMPORT
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
            'file' => 'required|file|mimes:xlsx,csv,xls|max:102400',
        ]);

        $path = null;

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) {
                throw new \Exception("File gagal disimpan di server.");
            }

            // Panggil Service Retur
            // Pastikan Anda sudah membuat class ReturImportService yang mirip PenjualanImportService
            $importService = new ReturImportService();
            $stats = $importService->handle($fullPath); 

            if (Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            $msg = "Proses Retur Selesai!\n" .
                   "📊 Total Baris: " . number_format($stats['total_rows']) . "\n" .
                   "✅ Masuk Database: " . number_format($stats['processed']) . "\n" .
                   "🗑️ Skipped (Non-Item): " . number_format($stats['skipped_no_item']);
            
            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => 'Import Berhasil',
                'message' => $msg
            ]);

        } catch (\Exception $e) {
            // Cleanup
            if (isset($filename) && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }

            Log::error('Import Retur Gagal: ' . $e->getMessage());
            $this->addError('file', 'Gagal: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            Retur::destroy($id);
            session()->flash('success', 'Data retur berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}