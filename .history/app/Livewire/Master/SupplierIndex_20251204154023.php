<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Supplier;
use App\Models\Master\Produk; // Digunakan untuk sinkronisasi
use Illuminate\Support\Facades\DB;
use Throwable;

class SupplierIndex extends Component
{
    use WithPagination;

    public $search = '';
    
    public function render()
    {
        $suppliers = Supplier::query()
            ->where('supplier_name', 'like', '%'.$this->search.'%')
            ->orderBy('supplier_name', 'asc')
            ->paginate(10);

        return view('livewire.master.supplier-index', [
            'suppliers' => $suppliers
        ])->layout('layouts.app', ['header' => 'Manajemen Supplier']);
    }

    /**
     * FUNGSI SINKRONISASI PENTING
     * Mengambil daftar supplier unik dari tabel produk dan memasukkannya ke tabel suppliers.
     */
    public function syncFromProducts()
    {
        try {
            DB::beginTransaction();

            // 1. Ambil semua nama supplier unik dari tabel produks (kecuali yang kosong)
            $uniqueSuppliers = Produk::select('supplier')
                                    ->whereNotNull('supplier')
                                    ->where('supplier', '!=', '')
                                    ->distinct()
                                    ->pluck('supplier');

            $importedCount = 0;

            // 2. Insert atau Abaikan (Ignore) ke tabel suppliers
            foreach ($uniqueSuppliers as $name) {
                // Gunakan updateOrCreate untuk menghindari duplikasi
                Supplier::updateOrCreate(
                    ['supplier_name' => trim((string)$name)],
                    // Data yang di-update (bisa diisi null jika tidak ada perubahan)
                    ['supplier_name' => trim((string)$name)]
                );
                $importedCount++;
            }

            DB::commit();
            session()->flash('success', "Sinkronisasi berhasil! Ditemukan dan diimpor $importedCount Supplier unik dari data Produk.");

        } catch (Throwable $e) {
            DB::rollBack();
            // Tangkap Error SQL jika ada
            session()->flash('error', 'Gagal Sinkronisasi: ' . $e->getMessage());
        }
    }
    
    // Tambahkan fitur hapus (opsional)
    public function delete($id)
    {
        try {
            Supplier::destroy($id);
            session()->flash('success', 'Supplier berhasil dihapus.');
        } catch (Throwable $e) {
            session()->flash('error', 'Gagal hapus supplier. Mungkin masih terkait dengan data lain.');
        }
    }
}