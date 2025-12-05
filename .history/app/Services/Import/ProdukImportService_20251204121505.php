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
        ini_set('memory_limit', '-1');
        set_time_limit(600);

        try {
            // 1. Baca File
            $reader = SimpleExcelReader::create($filePath);
            
            // PENTING: Hapus spasi di awal/akhir nama kolom header Excel otomatis
            // Contoh: "STOK " menjadi "STOK"
            $reader->trimHeaderRow();

            // DEBUG: Catat Header yang terbaca ke file log (storage/logs/laravel.log)
            // Ini untuk memastikan apakah header terbaca dengan benar
            $headers = $reader->getHeaders();
            Log::info('Import Start - Headers Detected:', $headers);

            $rows = $reader->getRows();
            $count = 0;

            foreach ($rows as $index => $row) {
                // DEBUG: Cek baris pertama untuk memastikan data masuk
                if ($index === 0) {
                    Log::info('First Row Data:', $row);
                }

                // Normalisasi Key menjadi Huruf Besar semua untuk pencocokan
                // Karena kadang Excel menulis "Sku", kadang "SKU"
                $row = array_change_key_case($row, CASE_UPPER);

                // Skip jika SKU kosong
                if (empty($row['SKU'])) {
                    // Log jika ada yang diskip
                    // Log::warning("Row $index skipped: No SKU");
                    continue;
                }

                // LOGIC MAPPING (Sudah dinormalisasi tanpa spasi aneh)
                Produk::updateOrCreate(
                    ['sku' => trim($row['SKU'])], 
                    [
                        // Identitas
                        'cabang'            => $row['CABANG'] ?? null,
                        'ccode'             => $row['CCODE'] ?? null,
                        'kategori'          => $row['KATEGORI'] ?? null,
                        'name_item'         => $row['NAME ITEM'] ?? null,
                        'expired_date'      => $this->parseDate($row['EXPIRED'] ?? null),
                        
                        // Perhatikan: Key di sini harus HURUF BESAR dan TANPA SPASI DI UJUNG
                        // karena kita sudah pakai trimHeaderRow() dan array_change_key_case
                        'stok'              => $this->cleanNumber($row['STOK'] ?? 0),
                        'oum'               => $row['OUM'] ?? null,
                        
                        // Good Stock
                        'good'              => $this->cleanNumber($row['GOOD'] ?? 0),
                        'good_konversi'     => $row['GOOD KONVERSI'] ?? null,
                        'ktn'               => $this->cleanNumber($row['KTN'] ?? 0),
                        'good_amount'       => $this->cleanNumber($row['GOOD AMOUNT'] ?? 0),
                        
                        // Avg 3M
                        'avg_3m_in_oum'     => $this->cleanNumber($row['AVG 3M IN OUM'] ?? 0),
                        'avg_3m_in_ktn'     => $this->cleanNumber($row['AVG 3M IN KTN'] ?? 0),
                        'avg_3m_in_value'   => $this->cleanNumber($row['AVG 3M IN VALUE'] ?? 0),
                        'not_move_3m'       => $row['NOT MOVE 3M'] ?? null,
                        
                        // Bad Stock
                        'bad'               => $this->cleanNumber($row['BAD'] ?? 0),
                        'bad_konversi'      => $row['BAD KONVERSI'] ?? null,
                        'bad_ktn'           => 0, 
                        'bad_amount'        => $this->cleanNumber($row['BAD AMOUNT'] ?? 0),
                        
                        // Warehouses
                        'wrh1'              => $this->cleanNumber($row['WRH1'] ?? 0),
                        'wrh1_konversi'     => $row['WRH1 KONVERSI'] ?? null,
                        'wrh1_amount'       => $this->cleanNumber($row['WRH1 AMOUNT'] ?? 0),
                        
                        'wrh2'              => $this->cleanNumber($row['WRH2'] ?? 0),
                        'wrh2_konversi'     => $row['WRH2 KONVERSI'] ?? null,
                        'wrh2_amount'       => $this->cleanNumber($row['WRH2 AMOUNT'] ?? 0),
                        
                        'wrh3'              => $this->cleanNumber($row['WRH3'] ?? 0),
                        'wrh3_konversi'     => $row['WRH3 KONVERSI'] ?? null,
                        'wrh3_amount'       => $this->cleanNumber($row['WRH3 AMOUNT'] ?? 0),
                        
                        // Others
                        'good_storage'      => $row['GOOD STORAGE'] ?? null,
                        'sell_per_week'     => $this->cleanNumber($row['SELL PER WEEK'] ?? 0), 
                        'blank_field'       => $row['BLANK'] ?? null,
                        'empty_field'       => $row['EMPTY'] ?? null,
                        'min'               => $this->cleanNumber($row['MIN'] ?? 0),
                        're_qty'            => $this->cleanNumber($row['RE QTY'] ?? 0),
                        
                        // Buying
                        'buy'               => $this->cleanNumber($row['BUY'] ?? 0),
                        'buy_disc'          => $this->cleanNumber($row['BUY - DISC'] ?? 0),
                        'buy_in_ktn'        => $this->cleanNumber($row['BUY IN KTN'] ?? 0),
                        'avg'               => $this->cleanNumber($row['AVG'] ?? 0),
                        'total'             => $this->cleanNumber($row['TOTAL'] ?? 0),
                        
                        'up'                => $this->cleanNumber($row['UP'] ?? 0),
                        'fix'               => $this->cleanNumber($row['FIX'] ?? 0),
                        'ppn'               => $this->cleanNumber($row['PPN'] ?? 0),
                        'fix_exc_ppn'       => $this->cleanNumber($row['FIX (EXC PPN)'] ?? 0),
                        'margin'            => $this->cleanNumber($row['MARGIN'] ?? 0),
                        'percent_margin'    => $this->cleanNumber($row['% MARGIN'] ?? $row['%MARGIN'] ?? 0),
                        'order_qty'         => $this->cleanNumber($row['ORDER'] ?? 0),
                        
                        // Meta
                        'supplier'          => $row['SUPPLIER'] ?? null,
                        'mother_sku'        => $row['MOTHER SKU'] ?? null,
                        'last_supplier'     => $row['LAST SUPPLIER'] ?? null,
                        'divisi'            => $row['DIVISI'] ?? null,
                        'unique_id'         => $row['UNIQUE'] ?? null,
                    ]
                );
                $count++;
            }
            
            Log::info("Import Selesai. Total baris diproses: $count");
            return $count;

        } catch (\Exception $e) {
            Log::error('Service Import Error: ' . $e->getMessage());
            throw $e;
        }
    }

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