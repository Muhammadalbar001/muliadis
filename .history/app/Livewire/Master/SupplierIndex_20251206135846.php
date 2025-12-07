<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Supplier;
use App\Models\Master\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Throwable;

class SupplierIndex extends Component
{
    use WithPagination;

    // --- Properties ---
    public $search = '';
    public $filterCabang = ''; // Filter Cabang
    
    public $isOpen = false;
    
    // Form Input
    public $supplierId;
    public $cabang; // Tambahan Input Cabang
    public $supplier_name;
    public $contact_person;
    public $phone;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterCabang() { $this->resetPage(); }

    public function render()
    {
        $query = Supplier::query();

        // 1. Search
        if ($this->search) {
            $query->where('supplier_name', 'like', '%'.$this->search.'%');
        }

        // 2. Filter Cabang
        if ($this->filterCabang) {
            $query->where('cabang', $this->filterCabang);
        }

        $suppliers = $query->orderBy('cabang', 'asc')
                           ->orderBy('supplier_name', 'asc')
                           ->paginate(10);

        // Ambil Opsi Cabang untuk Filter (Dari tabel Supplier itu sendiri)
        $optCabang = Cache::remember('opt_cabang_supp', 60, function () {
            return Supplier::select('cabang')->distinct()
                ->whereNotNull('cabang')->where('cabang', '!=', '')
                ->orderBy('cabang')->pluck('cabang');
        });

        return view('livewire.master.supplier-index', [
            'suppliers' => $suppliers,
            'optCabang' => $optCabang
        ])->layout('layouts.app', ['header' => 'Master Supplier']);
    }

    // ==========================================
    // SYNC DARI PRODUK (LOGIC BARU)
    // ==========================================
    public function syncFromProducts()
    {
        try {
            // Ambil kombinasi unik (Cabang + Supplier) dari tabel Produk
            $dataProduk = Produk::select('cabang', 'supplier')
                                ->whereNotNull('supplier')->where('supplier', '!=', '')
                                ->whereNotNull('cabang')->where('cabang', '!=', '')
                                ->distinct()
                                ->get();

            if ($dataProduk->isEmpty()) {
                session()->flash('error', 'Tidak ada data supplier di tabel Produk.');
                return;
            }

            $count = 0;
            
            foreach ($dataProduk as $item) {
                // Upsert berdasarkan (Cabang + Nama Supplier)
                // Jika sudah ada -> biarkan, Jika belum -> buat baru
                $supplier = Supplier::firstOrCreate(
                    [
                        'cabang'        => trim($item->cabang),
                        'supplier_name' => trim($item->supplier)
                    ],
                    [
                        // Data default lainnya jika baru dibuat
                        'contact_person' => null,
                        'phone'          => null
                    ]
                );
                
                if ($supplier->wasRecentlyCreated) {
                    $count++;
                }
            }

            // Clear cache filter agar cabang baru muncul
            Cache::forget('opt_cabang_supp');

            session()->flash('success', "Sync Selesai! Ditemukan $count Supplier baru dari data Produk.");

        } catch (Throwable $e) {
            session()->flash('error', 'Gagal Sync: ' . $e->getMessage());
        }
    }

    // ==========================================
    // CRUD MANUAL
    // ==========================================

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; $this->resetInputFields(); }

    private function resetInputFields()
    {
        $this->supplierId = null;
        $this->cabang = '';
        $this->supplier_name = '';
        $this->contact_person = '';
        $this->phone = '';
    }

    public function store()
    {
        // Validasi Unik Kombinasi (Manual Check karena rule unique laravel agak ribet untuk composite)
        $this->validate([
            'cabang' => 'required',
            'supplier_name' => 'required',
        ]);

        // Cek Duplikat Manual
        $exists = Supplier::where('cabang', $this->cabang)
                          ->where('supplier_name', $this->supplier_name)
                          ->where('id', '!=', $this->supplierId)
                          ->exists();

        if ($exists) {
            $this->addError('supplier_name', 'Supplier ini sudah ada di cabang tersebut.');
            return;
        }

        Supplier::updateOrCreate(['id' => $this->supplierId], [
            'cabang' => $this->cabang,
            'supplier_name' => $this->supplier_name,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
        ]);

        session()->flash('success', 'Data Supplier berhasil disimpan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $id;
        $this->cabang = $supplier->cabang;
        $this->supplier_name = $supplier->supplier_name;
        $this->contact_person = $supplier->contact_person;
        $this->phone = $supplier->phone;
        $this->openModal();
    }

    public function delete($id)
    {
        try {
            Supplier::destroy($id);
            session()->flash('success', 'Supplier dihapus.');
        } catch (Throwable $e) {
            session()->flash('error', 'Gagal hapus.');
        }
    }
}