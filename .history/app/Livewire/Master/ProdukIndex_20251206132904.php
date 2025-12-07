<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Produk;
use App\Services\Import\ProdukImportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProdukIndex extends Component
{
    use WithFileUploads, WithPagination;

    // Properties
    public $search = '';
    public $isInputOpen = false;
    public $isImportOpen = false;
    
    // Form Input
    public $productId;
    public $kode_item;   
    public $nama_item;   
    public $satuan_jual; 
    public $harga_jual;  
    
    // Import
    public $file;
    public $iteration = 1;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $produks = Produk::query()
            ->where('name_item', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%') 
            ->orWhere('cabang', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.master.produk-index', [
            'produks' => $produks
        ])->layout('layouts.app', ['header' => 'Master Produk (Import Center)']);
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

        $filename = null;

        try {
            $filename = $this->file->store('temp-imports', 'local');
            $fullPath = Storage::disk('local')->path($filename);

            if (!file_exists($fullPath)) {
                throw new \Exception("Gagal menyimpan file di server.");
            }

            // PROSES IMPORT
            $importService = new ProdukImportService();
            $stats = $importService->handle($fullPath);

            // Bersihkan File
            if (Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }
            
            $this->closeImportModal();

            // --- HITUNG STATISTIK ---
            // Processed = Total baris Excel yang berhasil dibaca & dikirim ke DB
            // Database Rows = Jumlah data unik yang tersimpan
            
            // Hitung Duplikat (Baris Excel - Data Unik di DB yang bertambah/update)
            // Asumsi: 'processed' adalah total baris excel valid yang dikirim.
            // Karena upsert menggabungkan duplikat, selisihnya adalah duplikat.
            // Namun, untuk pesan sederhana kita laporkan apa yang terjadi di file Excel.
            
            $msgTitle = "Import Selesai!";
            $msgBody  = "📂 Total Baris Excel: " . number_format($stats['total_rows']) . "\n" .
                        "✅ Sukses Diproses: " . number_format($stats['processed']);

            if ($stats['skipped_empty'] > 0) {
                $msgBody .= "\n⚠️ Skipped (Data Kosong): " . number_format($stats['skipped_empty']);
            }
            
            // Jika sukses diproses < total baris (karena duplikat di excel yang di-merge upsert)
            // Ini wajar di sistem Upsert.
            
            // KIRIM NOTIFIKASI VIA BROWSER EVENT (Lebih Kuat dari Session Flash)
            $this->dispatch('show-toast', [
                'type' => 'success',
                'title' => $msgTitle,
                'message' => $msgBody
            ]);

        } catch (\Exception $e) {
            if ($filename && Storage::disk('local')->exists($filename)) {
                Storage::disk('local')->delete($filename);
            }

            Log::error('Import Gagal: ' . $e->getMessage());
            
            // Kirim Notif Error
            $this->dispatch('show-toast', [
                'type' => 'error',
                'title' => 'Import Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // ==========================================
    // LOGIKA MANUAL (CRUD)
    // ==========================================
    // ... (Fungsi create, edit, store, delete TETAP SAMA seperti sebelumnya) ...
    public function create() { $this->resetInputFields(); $this->isInputOpen = true; }
    public function edit($id) { 
        $produk = Produk::findOrFail($id); 
        $this->productId = $id; 
        $this->kode_item = $produk->sku;
        $this->nama_item = $produk->name_item;
        $this->satuan_jual = $produk->oum;
        $this->harga_jual = $produk->buy;
        $this->isInputOpen = true; 
    }
    public function store() {
        $this->validate(['kode_item' => 'required|unique:produks,sku,'.$this->productId, 'nama_item' => 'required']);
        Produk::updateOrCreate(['id' => $this->productId], [
            'sku' => $this->kode_item, 'name_item' => $this->nama_item, 
            'oum' => $this->satuan_jual, 'buy' => $this->harga_jual ?? 0, 'stok' => '0'
        ]);
        $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data disimpan.']);
        $this->closeInputModal();
    }
    public function delete($id) {
        try { Produk::find($id)->delete(); $this->dispatch('show-toast', ['type' => 'success', 'title' => 'Dihapus', 'message' => 'Produk dihapus.']); }
        catch (\Exception $e) { $this->dispatch('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]); }
    }
    public function closeInputModal() { $this->isInputOpen = false; $this->resetInputFields(); }
    private function resetInputFields() { $this->productId = null; $this->kode_item = ''; $this->nama_item = ''; $this->satuan_jual = ''; $this->harga_jual = ''; }
}