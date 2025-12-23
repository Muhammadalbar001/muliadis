<?php

namespace App\Livewire\Pimpinan;

use Livewire\Component;
use App\Models\Master\Produk;
use Illuminate\Support\Facades\Response;

class ProfitAnalysis extends Component
{
    // State per cabang
    public $search = []; 
    public $selectedSuppliers = []; 
    public $filterMode = []; 
    public $selectedProductIds = [];

    // FITUR BARU: Sorting
    // desc = Tertinggi ke Terendah (Default), asc = Terendah ke Tertinggi
    public $sortDirection = 'desc'; 

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
            $this->filterMode[$b] = 'all'; 
            $this->selectedProductIds[$b] = [];
        }
    }

    public function updatedSelectedSuppliers($value, $key)
    {
        $parts = explode('.', $key);
        if(isset($parts[1])) {
            $branch = $parts[1];
            $this->selectedProductIds[$branch] = [];
            $this->filterMode[$branch] = 'all';
        }
    }

    // --- 1. FUNCTION SORTING ---
    public function toggleSort()
    {
        $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
    }

    // --- 2. FUNCTION EXPORT CSV ---
    public function export($branch)
    {
        // Ambil Data Sesuai Filter
        $suppliersSelected = $this->selectedSuppliers[$branch] ?? [];
        
        if (empty($suppliersSelected)) {
            return; 
        }

        $baseQuery = Produk::query()
            ->where('cabang', $branch)
            ->whereIn('supplier', $suppliersSelected);

        // Filter Mode
        $mode = $this->filterMode[$branch] ?? 'all';
        $productIdsSelected = $this->selectedProductIds[$branch] ?? [];

        if ($mode === 'selected' && !empty($productIdsSelected)) {
            $baseQuery->whereIn('id', $productIdsSelected);
        } elseif ($mode === 'selected' && empty($productIdsSelected)) {
            $baseQuery->whereRaw('1 = 0');
        }

        // Search Logic
        $searchQuery = $this->search[$branch] ?? '';
        if (!empty($searchQuery)) {
            $baseQuery->where(function($q) use ($searchQuery) {
                $q->where('name_item', 'like', '%' . $searchQuery . '%')
                  ->orWhere('sku', 'like', '%' . $searchQuery . '%');
            });
        }

        $products = $baseQuery->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8", // Pastikan charset UTF-8
            "Content-Disposition" => "attachment; filename=Laba_Rugi_" . str_replace(' ', '_', $branch) . "_" . date('d-m-Y_H-i') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // 1. TAMBAHKAN BOM (Byte Order Mark) AGAR EXCEL BACA UTF-8 DENGAN BENAR
            fputs($file, "\xEF\xBB\xBF");

            // 2. GUNAKAN TITIK KOMA (;) SEBAGAI PEMISAH
            $delimiter = ';';

            // Header Kolom
            fputcsv($file, [
                'LAST SUPPLIER', 
                'NAMA ITEM', 
                'STOK', 
                'MODAL DASAR (AVG)', 
                'PPN (%)', 
                'HPP FINAL (+PPN)', 
                'HARGA JUAL', 
                'MARGIN (RP)', 
                'MARGIN (%)'
            ], $delimiter);

            foreach ($products as $item) {
                // Logic Hitung
                $modalDasar = (float) $item->avg > 0 ? (float) $item->avg : (float) $item->buy;
                $rawPpn = $item->ppn; 
                $persenPpn = 0;
                if (is_numeric($rawPpn) && $rawPpn > 0) { $persenPpn = (float) $rawPpn; } 
                elseif (strtoupper(trim($rawPpn)) === 'Y') { $persenPpn = 11; }

                $nominalPpn = $modalDasar * ($persenPpn / 100);
                $hppFinal = $modalDasar + $nominalPpn;
                $hargaJual = (float) $item->fix; 
                $marginRp = $hargaJual - $hppFinal;
                $marginPersen = ($hppFinal > 0) ? ($marginRp / $hppFinal) * 100 : 0;

                // Tulis Baris (Gunakan $delimiter ;)
                fputcsv($file, [
                    $item->supplier,
                    $item->name_item,
                    $item->stok,
                    // Kita kirim angka murni agar bisa dihitung di Excel (tanpa Rp/Titik)
                    // Atau jika ingin format teks Indonesia, gunakan number_format dengan koma desimal
                    number_format($modalDasar, 2, ',', ''),
                    $persenPpn . '%',
                    number_format($hppFinal, 2, ',', ''),
                    number_format($hargaJual, 2, ',', ''),
                    number_format($marginRp, 2, ',', ''),
                    number_format($marginPersen, 2, ',', '') . '%'
                ], $delimiter);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
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
            
            // List Supplier
            $suppliersList = Produk::select('supplier')
                ->where('cabang', $branch)
                ->whereNotNull('supplier')
                ->where('supplier', '!=', '')
                ->distinct()
                ->orderBy('supplier', 'asc')
                ->pluck('supplier')
                ->toArray();

            // Logic Data
            $products = collect();
            $productsListForDropdown = [];

            $suppliersSelected = $this->selectedSuppliers[$branch] ?? [];
            $searchQuery = $this->search[$branch] ?? '';
            $mode = $this->filterMode[$branch] ?? 'all';
            $productIdsSelected = $this->selectedProductIds[$branch] ?? [];

            if (!empty($suppliersSelected)) {
                
                $baseQuery = Produk::query()
                    ->where('cabang', $branch)
                    ->whereIn('supplier', $suppliersSelected);

                // List untuk dropdown filter produk
                $productsListForDropdown = (clone $baseQuery)
                    ->orderBy('name_item', 'asc')
                    ->get(['id', 'name_item'])
                    ->toArray();

                // Filter Mode
                if ($mode === 'selected' && !empty($productIdsSelected)) {
                    $baseQuery->whereIn('id', $productIdsSelected);
                } elseif ($mode === 'selected' && empty($productIdsSelected)) {
                    $baseQuery->whereRaw('1 = 0');
                }

                // Search
                if (!empty($searchQuery)) {
                    $baseQuery->where(function($q) use ($searchQuery) {
                        $q->where('name_item', 'like', '%' . $searchQuery . '%')
                          ->orWhere('sku', 'like', '%' . $searchQuery . '%');
                    });
                }

                $baseQuery->orderBy('supplier', 'asc')
                          ->orderBy('name_item', 'asc');

                // Transformasi Data
                $products = $baseQuery->get()->map(function ($item) {
                    $modalDasar = (float) $item->avg > 0 ? (float) $item->avg : (float) $item->buy;
                    $rawPpn = $item->ppn; 
                    $persenPpn = 0;
                    if (is_numeric($rawPpn) && $rawPpn > 0) { $persenPpn = (float) $rawPpn; } 
                    elseif (strtoupper(trim($rawPpn)) === 'Y') { $persenPpn = 11; }

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

                // --- 3. SORTING LOGIC ---
                // Kita sort Collection hasil map (karena margin_persen adalah calculated field)
                if ($this->sortDirection === 'desc') {
                    $products = $products->sortByDesc('margin_persen')->values();
                } else {
                    $products = $products->sortBy('margin_persen')->values();
                }
            }

            $output[$branch] = [
                'suppliers_list' => $suppliersList,
                'products_list_dropdown' => $productsListForDropdown,
                'products' => $products
            ];
        }

        return view('livewire.pimpinan.profit-analysis', [
            'dataPerCabang' => $output
        ])->layout('layouts.app', ['header' => 'Profit Analysis']);
    }
}