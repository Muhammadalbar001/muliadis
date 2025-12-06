<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Supplier;
use Illuminate\Support\Facades\DB;
use Throwable;

class SupplierIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false; // WAJIB ADA untuk Modal

    // Form Properties
    public $supplierId;
    public $kode_supplier;
    public $nama_supplier;
    public $alamat;
    public $kota;
    public $telepon;

    public function render()
    {
        $suppliers = Supplier::query()
            ->where('nama_supplier', 'like', '%'.$this->search.'%') // Sesuaikan kolom DB
            ->orderBy('nama_supplier', 'asc')
            ->paginate(10);

        return view('livewire.master.supplier-index', [
            'suppliers' => $suppliers
        ])->layout('layouts.app', ['header' => 'Manajemen Supplier']);
    }

    // --- CRUD ---

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
        $this->kode_supplier = '';
        $this->nama_supplier = '';
        $this->alamat = '';
        $this->kota = '';
        $this->telepon = '';
    }

    public function store()
    {
        $this->validate([
            'kode_supplier' => 'required|unique:suppliers,kode_supplier,' . $this->supplierId,
            'nama_supplier' => 'required',
        ]);

        Supplier::updateOrCreate(['id' => $this->supplierId], [
            'kode_supplier' => $this->kode_supplier,
            'nama_supplier' => $this->nama_supplier,
            'alamat' => $this->alamat,
            // 'kota' => $this->kota, // Uncomment jika ada di DB
            // 'telepon' => $this->telepon, // Uncomment jika ada di DB
        ]);

        session()->flash('success', $this->supplierId ? 'Supplier berhasil diperbarui.' : 'Supplier berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $id;
        $this->kode_supplier = $supplier->kode_supplier;
        $this->nama_supplier = $supplier->nama_supplier;
        $this->alamat = $supplier->alamat;
        // $this->kota = $supplier->kota;
        // $this->telepon = $supplier->telepon;

        $this->openModal();
    }

    public function delete($id)
    {
        try {
            Supplier::destroy($id);
            session()->flash('success', 'Supplier berhasil dihapus.');
        } catch (Throwable $e) {
            session()->flash('error', 'Gagal hapus supplier. Mungkin data terkait transaksi.');
        }
    }
}