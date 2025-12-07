<?php

namespace App\Livewire\Transaksi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Keuangan\AccountReceivable;
use App\Services\Import\ArImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ArIndex extends Component
{
    use WithFileUploads, WithPagination;

    // Properties
    public $search = '';
    public $filterCabang = []; // Array untuk Multi-Select Filter
    public $isImportOpen = false;
    
    // Import
    public $file;
    public $iteration = 1;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }

    public function render()
    {
        $query = AccountReceivable::query();

        // 1. Search Logic
        if ($this->search) {
            $query->where(function($q) {
                $q->where('no_penjualan', 'like', '%' . $this->search . '%')
                  ->orWhere('pelanggan_name', 'like', '%' . $this->search . '%')
                  ->orWhere('sales_name', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Filter Cabang Logic
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        // 3. Ambil Data (Gunakan variabel $ars agar cocok dengan View)
        $ars = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // 4. Ambil Opsi Filter
        $optCabang = Cache::remember('ar_opt_cabang', 60, fn() => AccountReceivable::select('cabang')->distinct()->pluck('cabang'));

        return view('livewire.transaksi.ar-index', [
            'ars' => $ars,       // VAR PENTING: Harus 'ars'
            'optCabang' => $optCabang
        ])->layout('layouts.app', ['header' => 'Monitoring Piutang (AR)']);
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
        $this->validate(['file' => 'required|file|mimes:xlsx,csv,xls|max:102400']);
        $filename = null;

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            $importService = new ArImportService();
            $stats = $importService->handle($fullPath);

            if (Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            $this->closeImportModal();
            Cache::forget('ar_opt_cabang'); // Reset cache filter saat data baru masuk

            $msg = "Import AR Selesai!\n" .
                   "📊 Total Baris: " . number_format($stats['total_rows']) . "\n" .
                   "✅ Masuk Database: " . number_format($stats['processed']);

            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Sukses', 'message' => $msg]);

        } catch (\Exception $e) {
            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            Log::error($e);
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            AccountReceivable::destroy($id);
            $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Data AR dihapus.']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }
}