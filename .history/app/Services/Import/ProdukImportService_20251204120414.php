<?php

namespace App\Services\Import;

use App\Models\Master\Produk;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProdukImportService
{
    public function handle(string $filePath)
    {
        // Setup Memory & Time Limit untuk proses berat
        ini_set('memory_limit', '-1');
        set_time_limit(600);

        try {
            $reader = SimpleExcelReader::create($filePath);
            $rows = $reader->getRows();
            $count = 0;

            foreach ($rows as $row) {
                // Skip jika SKU kosong
                if (empty($row['SKU'])) continue;

                // LOGIC MAPPING (Dipindah ke sini)
                Produk::updateOrCreate(
                    ['sku' => trim($row['SKU'])], 
                    [
                        // Identitas
                        'cabang'            => $row['Cabang'] ?? null,
                        'ccode'             => $row['CCODE'] ?? null,
                        'kategori'          => $row['KATEGORI'] ?? null,
                        'name_item'         => $row['NAME ITEM'] ?? null,
                        'expired_date'      => $this->parseDate($row['EXPIRED'] ?? null),
                        'stok'              => $this->cleanNumber($row['STOK'] ?? $row['STOK '] ?? 0),
                        'oum'               => $row['OUM'] ?? $row['OUM '] ?? null,
                        
                        // Good Stock
                        'good'              => $this->cleanNumber($row['GOOD'] ?? $row['GOOD '] ?? 0),
                        'good_konversi'     => $row['GOOD KONVERSI'] ?? $row['GOOD KONVERSI '] ?? null,
                        'ktn'               => $this->cleanNumber($row['KTN'] ?? 0),
                        'good_amount'       => $this->cleanNumber($row['GOOD AMOUNT'] ?? $row['GOOD AMOUNT '] ?? 0),
                        
                        // Avg 3M
                        'avg_3m_in_oum'     => $this->cleanNumber($row['AVG 3M in OUM '] ?? 0),
                        'avg_3m_in_ktn'     => $this->cleanNumber($row['AVG 3M in KTN '] ?? 0),
                        'avg_3m_in_value'   => $this->cleanNumber($row['AVG 3M in VALUE '] ?? 0),
                        'not_move_3m'       => $row['NOT MOVE 3M'] ?? null,
                        
                        // Bad Stock
                        'bad'               => $this->cleanNumber($row['BAD'] ?? $row['BAD '] ?? 0),
                        'bad_konversi'      => $row['BAD KONVERSI'] ?? $row['BAD KONVERSI '] ?? null,
                        'bad_ktn'           => 0, 
                        'bad_amount'        => $this->cleanNumber($row['BAD AMOUNT'] ?? $row['BAD AMOUNT '] ?? 0),
                        
                        // Warehouses
                        'wrh1'              => $this->cleanNumber($row['WRH1'] ?? $row['WRH1 '] ?? 0),
                        'wrh1_konversi'     => $row['WRH1 KONVERSI'] ?? $row['WRH1 KONVERSI '] ?? null,
                        'wrh1_amount'       => $this->cleanNumber($row['WRH1 AMOUNT'] ?? $row['WRH1 AMOUNT '] ?? 0),
                        
                        'wrh2'              => $this->cleanNumber($row['WRH2'] ?? $row['WRH2 '] ?? 0),
                        'wrh2_konversi'     => $row['WRH2 KONVERSI'] ?? $row['WRH2 KONVERSI '] ?? null,
                        'wrh2_amount'       => $this->cleanNumber($row['WRH2 AMOUNT'] ?? $row['WRH2 AMOUNT '] ?? 0),
                        
                        'wrh3'              => $this->cleanNumber($row['WRH3'] ?? $row['WRH3 '] ?? 0),
                        'wrh3_konversi'     => $row['WRH3 KONVERSI'] ?? $row['WRH3 KONVERSI '] ?? null,
                        'wrh3_amount'       => $this->cleanNumber($row['WRH3 AMOUNT'] ?? $row['WRH3 AMOUNT '] ?? 0),
                        
                        // Others
                        'good_storage'      => $row['GOOD STORAGE'] ?? $row['GOOD STORAGE '] ?? null,
                        'sell_per_week'     => $this->cleanNumber($row['SELL PER WEEK'] ?? $row['SELL PER WEEK '] ?? 0), 
                        'blank_field'       => $row['Blank'] ?? $row['Blank '] ?? null,
                        'empty_field'       => $row['EMPTY'] ?? $row['EMPTY '] ?? null,
                        'min'               => $this->cleanNumber($row['MIN'] ?? $row['MIN '] ?? 0),
                        're_qty'            => $this->cleanNumber($row['RE QTY'] ?? $row['RE QTY '] ?? 0),
                        'expired_info'      => null,
                        
                        // Buying
                        'buy'               => $this->cleanNumber($row['BUY'] ?? $row['BUY '] ?? 0),
                        'buy_disc'          => $this->cleanNumber($row['BUY - DISC'] ?? $row['BUY - DISC '] ?? 0),
                        'buy_in_ktn'        => $this->cleanNumber($row['BUY in KTN'] ?? $row['BUY in KTN '] ?? 0),
                        'avg'               => $this->cleanNumber($row['AVG'] ?? $row['AVG '] ?? 0),
                        'total'             => $this->cleanNumber($row['TOTAL'] ?? $row['TOTAL '] ?? 0),
                        
                        'up'                => $this->cleanNumber($row['UP'] ?? $row['UP '] ?? 0),
                        'fix'               => $this->cleanNumber($row['FIX'] ?? $row['FIX '] ?? 0),
                        'ppn'               => $this->cleanNumber($row['PPN'] ?? $row['PPN '] ?? 0),
                        'fix_exc_ppn'       => $this->cleanNumber($row['FIX (EXC PPN)'] ?? $row['FIX (EXC PPN) '] ?? 0),
                        'margin'            => $this->cleanNumber($row['MARGIN'] ?? $row['MARGIN '] ?? 0),
                        'percent_margin'    => $this->cleanNumber($row['% MARGIN'] ?? $row['%MARGIN'] ?? 0),
                        'order_qty'         => $this->cleanNumber($row['ORDER'] ?? 0),
                        
                        // Meta
                        'supplier'          => $row['SUPPLIER'] ?? null,
                        'mother_sku'        => $row['Mother SKU'] ?? null,
                        'last_supplier'     => $row['LAST SUPPLIER'] ?? null,
                        'divisi'            => $row['Divisi'] ?? null,
                        'unique_id'         => $row['Unique'] ?? null,
                    ]
                );
                $count++;
            }

            return $count; // Kembalikan jumlah data yang berhasil

        } catch (\Exception $e) {
            // Lempar error agar ditangkap oleh Livewire
            throw $e;
        }
    }

    // Fungsi helper dipindah ke sini (Private Methods)
    private function cleanNumber($value)
    {
        if (is_null($value) || $value === '' || $value === '-') return 0;
        if (is_string($value)) {
            $cleaned = preg_replace('/[^0-9.-]/', '', $value);
            return (float) $cleaned;
        }
        return (float) $value;
    }

    private function parseDate($value)
    {
        if (!$value || $value === '-') return null;
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}