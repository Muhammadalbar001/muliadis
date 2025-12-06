<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Keuangan\AccountReceivable;
use App\Services\Import\ArImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $isImportOpen = false;
    
    public $file;
    public $iteration = 1;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // PERBAIKAN: Sesuaikan dengan nama kolom di Migration 'account_receivables'
        $ars = AccountReceivable::query()
            ->where('no_penjualan', 'like', '%' . $this->search . '%') // SEBELUMNYA: no_inv
            ->orWhere('pelanggan_name', 'like', '%' . $this->search . '%') // SEBELUMNYA: customer_name
            ->orWhere('sales_name', 'like', '%' . $this->search . '%')
            ->orderBy('tgl_penjualan', 'desc') // SEBELUMNYA: ar_date
            ->paginate(10);

        return view('livewire.transaksi.ar-index', [
            'ars' => $ars
        ])->layout('layouts.app', ['header' => 'Monitoring Piutang (AR)']);
    }

    // --- IMPORT LOGIC ---

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

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) throw new \Exception("Gagal simpan file.");

            // Pastikan Anda sudah membuat Service ini dan menyesuaikan mapping kolomnya juga
            $importService = new ArImportService();
            $stats = $importService->handle($fullPath);

            if (Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }

            $this->closeImportModal();
            session()->flash('success', "Import AR Selesai! Total: {$stats['total_rows']}, Sukses: {$stats['imported']}");

        } catch (\Exception $e) {
            // Bersihkan file jika error
            if (isset($filename) && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            Log::error('Import AR Gagal: ' . $e->getMessage());
            $this->addError('file', 'Error: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            AccountReceivable::destroy($id);
            session()->flash('success', 'Data piutang dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal hapus data.');
        }
    }
}