<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Keuangan\AccountReceivable; // Pastikan Model ini ada
use App\Services\Import\ArImportService;   // Pastikan Service ini ada
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $isImportOpen = false;
    
    // Upload properties
    public $file;
    public $iteration = 1;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query AR
        $ars = AccountReceivable::query()
            ->where('no_inv', 'like', '%' . $this->search . '%')
            ->orWhere('customer_name', 'like', '%' . $this->search . '%')
            ->orWhere('sales_name', 'like', '%' . $this->search . '%')
            ->orderBy('ar_date', 'desc') // Urutkan dari yang terbaru
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

            // Panggil Service Import AR
            $importService = new ArImportService();
            $stats = $importService->handle($fullPath);

            if (Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }

            $this->closeImportModal();
            session()->flash('success', "Import AR Selesai! Total: {$stats['total_rows']}, Sukses: {$stats['imported']}");

        } catch (\Exception $e) {
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