<?php

namespace App\Services\Import;

use App\Models\Master\Produk;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProdukImportService
{
    public function handle(string $filePath)
    {
        // 1. Setup Resource
        ini_set('memory_limit', '-1');
        set_time_limit(600);

        try {
            // 2. Baca File TANPA HEADER (Mode Index Angka)
            $reader = SimpleExcelReader::create($filePath)
                ->noHeaderRow(); 

            $rows = $reader->getRows();
            $count = 0;

            foreach ($rows as $index => $row) {
                // 3. Skip Baris Judul
                if (isset($row[0]) && strtolower(trim($row[0])) === 'cabang') {
                    continue;
                }

                // 4. Skip Baris Kosong (Jika SKU di Index 2 kosong)
                if (empty($row[2])) {
                    continue;
                }

                // 5. MAPPING DATA
                Produk::updateOrCreate(
                    ['sku' => trim($row[2])], // Index 2: SKU
                    [
                        // --- IDENTITAS ---
                        'cabang'            => $row[0] ?? null,
                        'ccode'             => $row[1] ?? null,
                        'kategori'          => $row[3] ?? null,
                        'name_item'         => $row[4] ?? null,
                        'expired_date'      => $this->parseDate($row[5] ?? null), // Fix Parse Date
                        'stok'              => $this->cleanNumber($row[6] ?? 0),
                        'oum'               => $row[7] ?? null,
                        
                        // --- GOOD STOCK ---
                        'good'              => $this->cleanNumber($row[8] ?? 0),
                        'good_konversi'     => $row[9] ?? null,
                        'ktn'               => $this->cleanNumber($row[10] ?? 0),
                        'good_amount'       => $this->cleanNumber($row[11] ?? 0),
                        
                        // --- AVG 3M ---
                        'avg_3m_in_oum'     => $this->cleanNumber($row[12] ?? 0),
                        'avg_3m_in_ktn'     => $this->cleanNumber($row[13] ?? 0),
                        'avg_3m_in_value'   => $this->cleanNumber($row[14] ?? 0),
                        'not_move_3m'       => $row[15] ?? null,
                        
                        // --- BAD STOCK ---
                        'bad'               => $this->cleanNumber($row[16] ?? 0),
                        'bad_konversi'      => $row[17] ?? null,
                        'bad_ktn'           => $this->cleanNumber($row[18] ?? 0),
                        'bad_amount'        => $this->cleanNumber($row[19] ?? 0),
                        
                        // --- WAREHOUSE 1 ---
                        'wrh1'              => $this->cleanNumber($row[20] ?? 0),
                        'wrh1_konversi'     => $row[21] ?? null,
                        'wrh1_amount'       => $this->cleanNumber($row[22] ?? 0),
                        
                        // --- WAREHOUSE 2 ---
                        'wrh2'              => $this->cleanNumber($row[23] ?? 0),
                        'wrh2_konversi'     => $row[24] ?? null,
                        'wrh2_amount'       => $this->cleanNumber($row[25] ?? 0),
                        
                        // --- WAREHOUSE 3 ---
                        'wrh3'              => $this->cleanNumber($row[26] ?? 0),
                        'wrh3_konversi'     => $row[27] ?? null,
                        'wrh3_amount'       => $this->cleanNumber($row[28] ?? 0),
                        
                        // --- STORAGE & SALES ---
                        'good_storage'      => $row[29] ?? null,
                        'sell_per_week'     => $this->cleanNumber($row[30] ?? 0),
                        'blank_field'       => $row[31] ?? null,
                        'empty_field'       => $row[32] ?? null,
                        'min'               => $this->cleanNumber($row[33] ?? 0),
                        're_qty'            => $this->cleanNumber($row[34] ?? 0),
                        'expired_info'      => $this->parseDate($row[35] ?? null), // Fix Parse Date
                        
                        // --- BUYING ---
                        'buy'               => $this->cleanNumber($row[36] ?? 0),
                        'buy_disc'          => $this->cleanNumber($row[37] ?? 0),
                        'buy_in_ktn'        => $this->cleanNumber($row[38] ?? 0),
                        'avg'               => $this->cleanNumber($row[39] ?? 0),
                        'total'             => $this->cleanNumber($row[40] ?? 0),
                        
                        // --- MARGIN & META ---
                        'up'                => $this->cleanNumber($row[41] ?? 0),
                        'fix'               => $this->cleanNumber($row[42] ?? 0),
                        'ppn'               => $this->cleanNumber($row[43] ?? 0),
                        'fix_exc_ppn'       => $this->cleanNumber($row[44] ?? 0),
                        'margin'            => $this->cleanNumber($row[45] ?? 0),
                        'percent_margin'    => $this->cleanNumber($row[46] ?? 0),
                        'order_qty'         => $this->cleanNumber($row[47] ?? 0),
                        'supplier'          => $row[48] ?? null,
                        'mother_sku'        => $row[49] ?? null,
                        'last_supplier'     => $row[50] ?? null,
                        'divisi'            => $row[51] ?? null,
                        'unique_id'         => $row[52] ?? null,
                    ]
                );
                $count++;
            }
            
            return $count;

        } catch (\Exception $e) {
            Log::error('Service Import Error: ' . $e->getMessage());
            throw $e;
        }
    }

    // Helper: Membersihkan Angka
    private function cleanNumber($value)
    {
        if (is_null($value) || $value === '' || $value === '-') return 0;
        
        if (is_string($value)) {
            $clean = preg_replace('/[^0-9.,-]/', '', $value);
            if (strpos($clean, ',') !== false && strpos($clean, '.') === false) {
                 $clean = str_replace(',', '.', $clean);
            } elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
                 $clean = str_replace('.', '', $clean);
                 $clean = str_replace(',', '.', $clean);
            }
            return (float) $clean;
        }
        return (float) $value;
    }

    // Helper: Membersihkan & Parse Tanggal (TERMASUK HAPUS KUTIP)
    private function parseDate($value)
    {
        // 1. Cek kosong
        if (!$value || $value === '-' || $value === 'Blank') return null;

        // 2. HAPUS TANDA KUTIP (') DI AWAL STRING
        // Contoh: '2027-06-30 menjadi 2027-06-30
        if (is_string($value)) {
            $value = ltrim($value, "'");
        }

        try {
            // 3. Cek format Excel Numeric (45201)
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            // 4. Parse string biasa (Y-m-d atau d/m/Y)
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}