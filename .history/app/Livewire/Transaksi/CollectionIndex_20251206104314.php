<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Keuangan\Collection; // Pastikan Model ada
use App\Services\Import\CollectionImportService; // Pastikan Service ada
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
        // Query Collection
        $collections = Collection::query()
            ->where('invoice_no', 'like', '%' . $this->search . '%')
            ->orWhere('customer_name', 'like', '%' . $this->search . '%')
            ->orWhere('payment_method', 'like', '%' . $this->search . '%')
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        return view('livewire.transaksi.collection-index', [
            'collections' => $collections
        ])->layout('layouts.app', ['header' => 'Data Collection (Lunas)']);
    }

    // --- IMPORT ---

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