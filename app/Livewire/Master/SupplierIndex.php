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

    public $search = '';
    
    // --- UBAH MENJADI ARRAY ---
    public $filterCabang = []; 
    // -------------------------
    
    public $isOpen = false;
    
    // Form Input
    public $supplierId;
    public $cabang;
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
            $query->where('supplier_name', 'like', '%'.$this->search.'%')
                  ->orWhere('contact_person', 'like', '%'.$this->search.'%'); // Tambah cari kontak
        }

        // 2. Filter Cabang (Multi-Select)
        if (!empty($this->filterCabang)) {
            $query->whereIn('cabang', $this->filterCabang);
        }

        $suppliers = $query->orderBy('cabang', 'asc')
                           ->orderBy('supplier_name', 'asc')
                           ->paginate(25);

        $optCabang = Cache::remember('opt_cabang_supp', 3600, function () {
            return Supplier::select('cabang')->distinct()
                ->whereNotNull('cabang')->where('cabang', '!=', '')
                ->orderBy('cabang')->pluck('cabang');
        });

        return view('livewire.master.supplier-index', compact('suppliers', 'optCabang'))
            ->layout('layouts.app', ['header' => 'Master Supplier']);
    }

    // --- SYNC DARI PRODUK ---
    public function syncFromProducts()
    {
        try {
            $dataProduk = Produk::select('cabang', 'supplier')
                ->whereNotNull('supplier')->where('supplier', '!=', '')
                ->whereNotNull('cabang')->where('cabang', '!=', '')
                ->distinct()->get();

            if ($dataProduk->isEmpty()) {
                $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Tidak ada data supplier di tabel Produk.']);
                return;
            }

            $count = 0;
            foreach ($dataProduk as $item) {
                $supplier = Supplier::firstOrCreate(
                    ['cabang' => trim($item->cabang), 'supplier_name' => trim($item->supplier)],
                    ['contact_person' => null, 'phone' => null]
                );
                if ($supplier->wasRecentlyCreated) $count++;
            }

            Cache::forget('opt_cabang_supp');
            $this->dispatch('show-toast', ['type' => 'success', 'message' => "Sync Selesai! $count Supplier baru ditambahkan."]);

        } catch (Throwable $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gagal Sync: ' . $e->getMessage()]);
        }
    }

    // --- CRUD ---
    public function create() { $this->resetInputFields(); $this->openModal(); }
    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; $this->resetInputFields(); }

    private function resetInputFields() {
        $this->reset(['supplierId', 'cabang', 'supplier_name', 'contact_person', 'phone']);
    }

    public function store() {
        $this->validate(['cabang' => 'required', 'supplier_name' => 'required']);

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

        $this->closeModal();
        Cache::forget('opt_cabang_supp');
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data Supplier disimpan.']);
    }

    public function edit($id) {
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $id;
        $this->cabang = $supplier->cabang;
        $this->supplier_name = $supplier->supplier_name;
        $this->contact_person = $supplier->contact_person;
        $this->phone = $supplier->phone;
        $this->openModal();
    }

    public function delete($id) {
        Supplier::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Supplier dihapus.']);
    }
}