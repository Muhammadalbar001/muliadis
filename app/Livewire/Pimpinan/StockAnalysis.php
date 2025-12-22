<?php

namespace App\Livewire\Pimpinan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Produk;

class StockAnalysis extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCabang = '';   
    public $selectedSuppliers = []; 
    public $perPage = 50;

    public function mount()
    {
        // Otomatis pilih cabang pertama agar tab tidak kosong
        $firstBranch = Produk::whereNotNull('cabang')->where('cabang', '!=', '')->orderBy('cabang')->first();
        if ($firstBranch) {
            $this->selectedCabang = $firstBranch->cabang;
        }
    }

    public function setCabang($namaCabang)
    {
        $this->selectedCabang = $namaCabang;
        $this->selectedSuppliers = []; // Reset supplier saat ganti cabang
        $this->resetPage();
    }

    // Update halaman saat supplier dipilih
    public function updatedSelectedSuppliers()
    {
        $this->resetPage();
    }

    public function render()
    {
        // 1. Ambil List Cabang
        $branches = Produk::select('cabang')
            ->whereNotNull('cabang')
            ->where('cabang', '!=', '')
            ->distinct()
            ->orderBy('cabang', 'asc')
            ->pluck('cabang');

        // 2. Ambil List Supplier (Sesuai Cabang Aktif)
        $suppliers = Produk::select('supplier')
            ->whereNotNull('supplier')
            ->where('supplier', '!=', '')
            ->where('cabang', $this->selectedCabang)
            ->distinct()
            ->orderBy('supplier', 'asc')
            ->pluck('supplier');

        // 3. Query Produk (HANYA JIKA SUPPLIER DIPILIH)
        $products = collect(); 

        if (!empty($this->selectedSuppliers)) {
            $query = Produk::query()
                ->where('cabang', $this->selectedCabang)
                ->whereIn('supplier', $this->selectedSuppliers);

            if ($this->search) {
                $query->where(function($q) {
                    $q->where('name_item', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            }

            $query->orderBy('supplier', 'asc')
                  ->orderBy('name_item', 'asc');

            $products = $query->paginate($this->perPage);

            // TRANSFORM DATA (RAW / MURNI DARI DB)
            $products->getCollection()->transform(function ($item) {
                return [
                    'id'            => $item->id,
                    'nama_item'     => $item->name_item,
                    'good_konversi' => $item->good_konversi,
                    'ktn'           => $item->ktn,
                    'sell_per_week' => $item->sell_per_week,
                    
                    // PERBAIKAN: Ambil langsung dari kolom DB 'empty_field'
                    // Jangan diotak-atik logicnya.
                    'empty_field'   => $item->empty_field, 
                    
                    'buy'           => $item->buy,
                    'buy_disc'      => $item->buy_disc,
                    'avg'           => $item->avg,
                    'fix'           => $item->fix,
                    'ppn'           => $item->ppn,
                    'supplier'      => $item->supplier,
                    'cabang'        => $item->cabang,
                ];
            });
        }

        return view('livewire.pimpinan.stock-analysis', [
            'branches'      => $branches,
            'suppliersList' => $suppliers,
            'products'      => $products
        ])->layout('layouts.app', ['header' => 'Stock Analysis']);
    }
}