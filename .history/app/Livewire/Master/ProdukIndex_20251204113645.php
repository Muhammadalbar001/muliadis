<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Master\Produk;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Storage;

class ProdukIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $file; // Variabel file upload
    public $isImporting = false;
    public $search = '';

    protected $rules = [
        'file' => 'required|mimes:xlsx,csv,xls|max:10240', // Max 10MB
    ];

    public function render()
    {
        $produks = Produk::query()
            ->where('name_item', 'like', '%'.$this->search.'%')
            ->orWhere('sku', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.master.produk-index', [
            'produks' => $produks
        ])->layout('layouts.app', ['header' => 'Manajemen Produk']);
    }

    public function import()
    {
        $this->validate();
        $this->isImporting = true;

        // Simpan file sementara
        $path = $this->file->store('temp-imports');
        $fullPath = storage_path('app/' . $path);

        // Baca Excel dengan Spatie Simple Excel
        $rows = SimpleExcelReader::create($fullPath)->getRows();

        foreach ($rows as $row) {
            // Logika Mapping Header Excel ke Database
            Produk::updateOrCreate(
                ['sku' => $row['SKU']], // Kunci Unik
                [
                    'cabang'            => $row['Cabang'] ?? null,
                    'ccode'             => $row['CCODE'] ?? null,
                    'kategori'          => $row['KATEGORI'] ?? null,
                    'name_item'         => $row['NAME ITEM'] ?? null,
                    'expired_date'      => $this->parseDate($row['EXPIRED'] ?? null),
                    'stok'              => $this->cleanNumber($row['STOK '] ?? 0),
                    'oum'               => $row['OUM '] ?? null,
                    
                    // Good Stock
                    'good'              => $this->cleanNumber($row['GOOD '] ?? 0),
                    'good_konversi'     => $row['GOOD KONVERSI '] ?? null,
                    'ktn'               => $this->cleanNumber($row['KTN '] ?? 0),
                    'good_amount'       => $this->cleanNumber($row['GOOD AMOUNT '] ?? 0),
                    
                    // Avg 3M
                    'avg_3m_in_oum'     => $this->cleanNumber($row['AVG 3M in OUM '] ?? 0),
                    'avg_3m_in_ktn'     => $this->cleanNumber($row['AVG 3M in KTN '] ?? 0),
                    'avg_3m_in_value'   => $this->cleanNumber($row['AVG 3M in VALUE '] ?? 0),
                    'not_move_3m'       => $row['NOT MOVE 3M'] ?? null,
                    
                    // Bad Stock
                    'bad'               => $this->cleanNumber($row['BAD '] ?? 0),
                    'bad_konversi'      => $row['BAD KONVERSI '] ?? null,
                    'bad_ktn'           => $this->cleanNumber($row['KTN '] ?? 0),
                    'bad_amount'        => $this->cleanNumber($row['BAD AMOUNT '] ?? 0),
                    
                    // Warehouses
                    'wrh1'              => $this->cleanNumber($row['WRH1 '] ?? 0),
                    'wrh1_konversi'     => $row['WRH1 KONVERSI '] ?? null,
                    'wrh1_amount'       => $this->cleanNumber($row['WRH1 AMOUNT '] ?? 0),
                    'wrh2'              => $this->cleanNumber($row['WRH2 '] ?? 0),
                    'wrh2_konversi'     => $row['WRH2 KONVERSI '] ?? null,
                    'wrh2_amount'       => $this->cleanNumber($row['WRH2 AMOUNT '] ?? 0),
                    'wrh3'              => $this->cleanNumber($row['WRH3 '] ?? 0),
                    'wrh3_konversi'     => $row['WRH3 KONVERSI '] ?? null,
                    'wrh3_amount'       => $this->cleanNumber($row['WRH3 AMOUNT '] ?? 0),
                    
                    // Others
                    'good_storage'      => $row['GOOD STORAGE '] ?? null,
                    'sell_per_week'     => $this->cleanNumber($row['SELL PER WEEK  '] ?? 0),
                    'blank_field'       => $row['Blank '] ?? null,
                    'empty_field'       => $row['EMPTY '] ?? null,
                    'min'               => $this->cleanNumber($row['MIN '] ?? 0),
                    're_qty'            => $this->cleanNumber($row['RE QTY '] ?? 0),
                    'expired_info'      => $this->parseDate($row['EXPIRED '] ?? null),
                    
                    // Buying
                    'buy'               => $this->cleanNumber($row['BUY '] ?? 0),
                    'buy_disc'          => $this->cleanNumber($row['BUY - DISC '] ?? 0),
                    'buy_in_ktn'        => $this->cleanNumber($row['BUY in KTN '] ?? 0),
                    'avg'               => $this->cleanNumber($row['AVG '] ?? 0),
                    'total'             => $this->cleanNumber($row['TOTAL '] ?? 0),
                    'up'                => $this->cleanNumber($row['UP'] ?? 0),
                    'fix'               => $this->cleanNumber($row['FIX '] ?? 0),
                    'ppn'               => $this->cleanNumber($row['PPN '] ?? 0),
                    'fix_exc_ppn'       => $this->cleanNumber($row['FIX (EXC PPN) '] ?? 0),
                    'margin'            => $this->cleanNumber($row['MARGIN '] ?? 0),
                    'percent_margin'    => $this->cleanNumber($row['%MARGIN'] ?? 0),
                    'order_qty'         => $this->cleanNumber($row['ORDER'] ?? 0),
                    
                    // Meta
                    'supplier'          => $row['SUPPLIER'] ?? null,
                    'mother_sku'        => $row['Mother SKU'] ?? null,
                    'last_supplier'     => $row['LAST SUPPLIER'] ?? null,
                    'divisi'            => $row['Divisi'] ?? null,
                    'unique_id'         => $row['Unique'] ?? null,
                ]
            );
        }

        Storage::delete($path);
        
        $this->isImporting = false;
        $this->file = null;
        
        session()->flash('success', 'Import Data Produk Berhasil!');
    }

    private function cleanNumber($value)
    {
        if (is_string($value)) {
            return (float) preg_replace('/[^0-9.-]/', '', $value);
        }
        return $value;
    }

    private function parseDate($value)
    {
        if (!$value) return null;
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}