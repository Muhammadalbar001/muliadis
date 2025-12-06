<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Supplier;
use App\Models\Master\Produk; 
use Illuminate\Support\Facades\DB;
use Throwable;

class SupplierIndex extends Component
{
    use WithPagination;

    // --- Properties Tampilan ---
    public $search = '';
    public $isOpen = false; 
    
    // --- Properties Form (Sesuai Database) ---
    public $supplierId;
    public $supplier_name;  // Database: supplier_name
    public $contact_person; // Database: contact_person
    public $phone;          // Database: phone

    public function render()
    {
        // Query disesuaikan dengan kolom 'supplier_name'
        $suppliers = Supplier::query()
            ->where('supplier_name', 'like', '%'.$this->search.'%')
            ->orderBy('supplier_name', 'asc')
            ->paginate(10);

        return view('livewire.master.supplier-index', [
            'suppliers' => $suppliers
        ])->layout('layouts.app', ['header' => 'Master Supplier']);
    }

    // ==========================================
    // BAGIAN 1: FITUR SYNC (AUTO IMPORT DARI PRODUK)
    // ==========================================
    
    public function syncFromProducts()
    {
        try {
            // 1. Ambil nama supplier unik dari tabel produk
            // Pastikan kolom di tabel produks bernama 'supplier' (sesuai migrasi produk)
            $uniqueSuppliers = Produk::select('supplier')
                                    ->whereNotNull('supplier')
                                    ->where('supplier', '!=', '')
                                    ->distinct()
                                    ->pluck('supplier');

            if ($uniqueSuppliers->isEmpty()) {
                session()->flash('error', 'Tidak ada data supplier ditemukan di tabel Produk.');
                return;
            }

            $count = 0;
            DB::beginTransaction();

            // 2. Masukkan ke tabel suppliers jika belum ada
            foreach ($uniqueSuppliers as $name) {
                $name = trim($name);
                
                // Cek apakah sudah ada
                $exists = Supplier::where('supplier_name', $name)->exists();
                
                if (!$exists) {
                    Supplier::create([
                        'supplier_name' => $name,
                        // contact & phone dibiarkan null dulu
                    ]);
                    $count++;
                }
            }

            DB::commit();

            if ($count > 0) {
                session()->flash('success', "BERHASIL! $count supplier baru ditambahkan dari data Produk.");
            } else {
                session()->flash('success', "Data sudah sinkron. Tidak ada supplier baru.");
            }

        } catch (Throwable $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal Sinkronisasi: ' . $e->getMessage());
        }
    }

    // ==========================================
    // BAGIAN 2: CRUD MANUAL
    // ==========================================

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->supplierId = null;
        $this->supplier_name = '';
        $this->contact_person = '';
        $this->phone = '';
    }

    public function store()
    {
        $this->validate([
            // Validasi nama supplier harus unik, kecuali untuk ID yang sedang diedit
            'supplier_name' => 'required|unique:suppliers,supplier_name,' . $this->supplierId,
        ]);

        Supplier::updateOrCreate(['id' => $this->supplierId], [
            'supplier_name'  => $this->supplier_name,
            'contact_person' => $this->contact_person,
            'phone'          => $this->phone,
        ]);

        session()->flash('success', $this->supplierId ? 'Supplier diperbarui.' : 'Supplier ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $id;
        
        // Mapping Kolom DB -> Form
        $this->supplier_name = $supplier->supplier_name;
        $this->contact_person = $supplier->contact_person;
        $this->phone = $supplier->phone;

        $this->openModal();
    }

    public function delete($id)
    {
        try {
            Supplier::destroy($id);
            session()->flash('success', 'Supplier berhasil dihapus.');
        } catch (Throwable $e) {
            session()->flash('error', 'Gagal hapus. Data mungkin dipakai di transaksi.');
        }
    }
}