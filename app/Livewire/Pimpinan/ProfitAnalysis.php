<?php

namespace App\Livewire\Pimpinan;

use Livewire\Component;
use App\Models\Master\Produk;

class ProfitAnalysis extends Component
{
    // State per cabang
    public $search = []; 
    public $selectedSuppliers = []; 
    
    // BARU: Mode Filter ('all' atau 'selected') per cabang
    public $filterMode = []; 
    
    // BARU: ID Produk yang dipilih (jika mode 'selected') per cabang
    public $selectedProductIds = [];

    public function mount()
    {
        $branches = Produk::select('cabang')
            ->whereNotNull('cabang')
            ->where('cabang', '!=', '')
            ->distinct()
            ->pluck('cabang');

        foreach ($branches as $b) {
            $this->search[$b] = '';
            $this->selectedSuppliers[$b] = [];
            
            // Default: Tampilkan Semua
            $this->filterMode[$b] = 'all'; 
            $this->selectedProductIds[$b] = [];
        }
    }

    // Reset produk terpilih jika supplier berubah
    public function updatedSelectedSuppliers($value, $key)
    {
        // $key formatnya: selectedSuppliers.Banjarmasin
        $parts = explode('.', $key);
        if(isset($parts[1])) {
            $branch = $parts[1];
            $this->selectedProductIds[$branch] = [];
            $this->filterMode[$branch] = 'all';
        }
    }

    public function render()
    {
        $branches = Produk::select('cabang')
            ->whereNotNull('cabang')
            ->where('cabang', '!=', '')
            ->distinct()
            ->orderBy('cabang', 'asc')
            ->pluck('cabang');

        $output = [];

        foreach ($branches as $branch) {
            
            // 1. List Supplier (Untuk Dropdown Supplier)
            $suppliersList = Produk::select('supplier')
                ->where('cabang', $branch)
                ->whereNotNull('supplier')
                ->where('supplier', '!=', '')
                ->distinct()
                ->orderBy('supplier', 'asc')
                ->pluck('supplier')
                ->toArray();

            // 2. Logic Data
            $products = collect();
            $productsListForDropdown = []; // Untuk Dropdown Pilihan Produk

            $suppliersSelected = $this->selectedSuppliers[$branch] ?? [];
            $searchQuery = $this->search[$branch] ?? '';
            $mode = $this->filterMode[$branch] ?? 'all';
            $productIdsSelected = $this->selectedProductIds[$branch] ?? [];

            if (!empty($suppliersSelected)) {
                
                // Query Dasar (Berdasarkan Supplier)
                $baseQuery = Produk::query()
                    ->where('cabang', $branch)
                    ->whereIn('supplier', $suppliersSelected);

                // A. Siapkan List Produk untuk Dropdown (Hanya ambil ID dan Nama)
                // Ini dipakai jika user memilih opsi "Filter Produk"
                $productsListForDropdown = (clone $baseQuery)
                    ->orderBy('name_item', 'asc')
                    ->get(['id', 'name_item']) // Ambil ID dan Nama saja biar ringan
                    ->toArray();

                // B. Filter Utama untuk Tabel
                // Jika Mode = Selected DAN ada produk dipilih -> Filter by ID
                if ($mode === 'selected' && !empty($productIdsSelected)) {
                    $baseQuery->whereIn('id', $productIdsSelected);
                }
                // Jika Mode = Selected TAPI belum pilih produk -> Jangan tampilkan apa-apa (biar user pilih dulu)
                elseif ($mode === 'selected' && empty($productIdsSelected)) {
                    $baseQuery->whereRaw('1 = 0'); // Force empty result
                }

                // C. Filter Search Text (Tetap jalan di kedua mode)
                if (!empty($searchQuery)) {
                    $baseQuery->where(function($q) use ($searchQuery) {
                        $q->where('name_item', 'like', '%' . $searchQuery . '%')
                          ->orWhere('sku', 'like', '%' . $searchQuery . '%');
                    });
                }

                $baseQuery->orderBy('supplier', 'asc')
                          ->orderBy('name_item', 'asc');

                // Ambil Data & Hitung Margin
                $products = $baseQuery->get()->map(function ($item) {
                    $modalDasar = (float) $item->avg > 0 ? (float) $item->avg : (float) $item->buy;
                    
                    // Logic PPN
                    $rawPpn = $item->ppn; 
                    $persenPpn = 0;
                    if (is_numeric($rawPpn) && $rawPpn > 0) {
                        $persenPpn = (float) $rawPpn;
                    } elseif (strtoupper(trim($rawPpn)) === 'Y') {
                        $persenPpn = 11; 
                    }

                    $nominalPpn = $modalDasar * ($persenPpn / 100);
                    $hppFinal = $modalDasar + $nominalPpn;
                    $hargaJual = (float) $item->fix; 
                    $marginRp = $hargaJual - $hppFinal;
                    $marginPersen = ($hppFinal > 0) ? ($marginRp / $hppFinal) * 100 : 0;

                    return [
                        'id'            => $item->id,
                        'last_supplier' => $item->supplier,
                        'name_item'     => $item->name_item,
                        'stock'         => $item->stok,
                        'avg_ppn'       => $hppFinal,
                        'harga_jual'    => $hargaJual,
                        'margin_rp'     => $marginRp,
                        'margin_persen' => $marginPersen,
                    ];
                });
            }

            $output[$branch] = [
                'suppliers_list' => $suppliersList,
                'products_list_dropdown' => $productsListForDropdown, // Data dropdown produk
                'products' => $products
            ];
        }

        return view('livewire.pimpinan.profit-analysis', [
            'dataPerCabang' => $output
        ])->layout('layouts.app', ['header' => 'Profit Analysis']);
    }
}