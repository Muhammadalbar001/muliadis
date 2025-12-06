<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Keuangan\Collection; 
use App\Services\Import\CollectionImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CollectionIndex extends Component
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
        // PERBAIKAN: Sesuaikan dengan kolom database 'collections'
        $collections = Collection::query()
            ->where('invoice_no', 'like', '%' . $this->search . '%')
            ->orWhere('outlet_name', 'like', '%' . $this->search . '%') // SEBELUMNYA: customer_name
            ->orWhere('sales_name', 'like', '%' . $this->search . '%')   // SEBELUMNYA: payment_method
            ->orWhere('receive_no', 'like', '%' . $this->search . '%')   // Tambahan: Cari No Bukti
            ->orderBy('tanggal', 'desc') // SEBELUMNYA: payment_date
            ->paginate(10);

        return view('livewire.transaksi.collection-index', [
            'collections' => $collections
        ])->layout('layouts.app', ['header' => 'Data Collection (Pelunasan)']);
    }

    // --- LOGIKA IMPORT ---

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

            if (!file_exists($fullPath)) throw new \Exception("File error.");

            // Pastikan Service Import Collection Anda juga diupdate mapping kolomnya
            $importService = new CollectionImportService();
            $stats = $importService->handle($fullPath);

            if (Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }

            $this->closeImportModal();
            session()->flash('success', "Import Collection Selesai. Total: {$stats['total_rows']}, Sukses: {$stats['imported']}");

        } catch (\Exception $e) {
            if (isset($filename) && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            Log::error('Import Collection Gagal: ' . $e->getMessage());
            $this->addError('file', 'Error: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            Collection::destroy($id);
            session()->flash('success', 'Data collection dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal hapus.');
        }
    }
}