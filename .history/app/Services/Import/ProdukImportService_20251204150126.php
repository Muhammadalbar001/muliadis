<?php

namespace App\Services\Import;

use App\Models\Master\Produk;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class ProdukImportService
{
    public function handle(string $filePath)
    {
        // 1. SETTING SUPER POWER (Unlimited)
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();
            $rows = $reader->getRows();
            
            $stats = [
                'total_rows' => 0,
                'imported' => 0,
                'skipped_empty' => 0,
                'skipped_error' => 0,
            ];

            DB::beginTransaction();

            foreach ($rows as $index => $rawRow) {
                try {
                    $stats['total_rows']++;
                    
                    // --- FUNGSI PENGAMAN BARIS (ANTI CRASH) ---
                    $val_safe = function ($row, $index) {
                        if (!array_key_exists($index, $row)) return '';
                        $value = $row[$index];
                        if (is_array($value) || is_object($value)) {
                            return json_encode($value, JSON_UNESCAPED_UNICODE);
                        }
                        return trim(strval($value));
                    };
                    // --- END FUNGSI PENGAMAN ---

                    $row = array_values((array)$rawRow);

                    $cabangRaw = $val_safe($row, 0);
                    $skuRaw    = $val_safe($row, 2);

                    if (strcasecmp($cabangRaw, 'cabang') === 0) { continue; }
                    if ($skuRaw === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // 3. SIMPAN DATA (Update Or Create)
                    Produk::updateOrCreate(
                        [
                            'sku'    => $skuRaw,
                            'cabang' => $cabangRaw ?: null
                        ],
                        [
                            'ccode'             => $this->val($row, 1),
                            'kategori'          => $this->val($row, 3),
                            'name_item'         => $this->val($row, 4),
                            'expired_date'      => $this->date($row, 5),
                            'stok'              => $this->num($row, 6),
                            'oum'               => $this->val($row, 7),
                            'good'              => $this->num($row, 8),
                            'good_konversi'     => $this->val($row, 9),
                            'ktn'               => $this->num($row, 10),
                            'good_amount'       => $this->num($row, 11),
                            'avg_3m_in_oum'     => $this->num($row, 12),
                            'avg_3m_in_ktn'     => $this->num($row, 13),
                            'avg_3m_in_value'   => $this->num($row, 14),
                            'not_move_3m'       => $this->val($row, 15),
                            'bad'               => $this->num($row, 16),
                            'bad_konversi'      => $this->val($row, 17),
                            'bad_ktn'           => $this->num($row, 18),
                            'bad_amount'        => $this->num($row, 19),
                            'wrh1'              => $this->num($row, 20),
                            'wrh1_konversi'     => $this->val($row, 21),
                            'wrh1_amount'       => $this->num($row, 22),
                            'wrh2'              => $this->num($row, 23),
                            'wrh2_konversi'     => $this->val($row, 24),
                            'wrh2_amount'       => $this->num($row, 25),
                            'wrh3'              => $this->num($row, 26),
                            'wrh3_konversi'     => $this->val($row, 27),
                            'wrh3_amount'       => $this->num($row, 28),
                            'good_storage'      => $this->val($row, 29),
                            'sell_per_week'     => $this->num($row, 30),
                            'blank_field'       => $this->val($row, 31),
                            'empty_field'       => $this->val($row, 32),
                            'min'               => $this->num($row, 33),
                            're_qty'            => $this->num($row, 34),
                            'expired_info'      => $this->date($row, 35),
                            'buy'               => $this->num($row, 36),
                            'buy_disc'          => $this->num($row, 37),
                            'buy_in_ktn'        => $this->num($row, 38),
                            'avg'               => $this->num($row, 39),
                            'total'             => $this->num($row, 40),
                            'up'                => $this->num($row, 41),
                            'fix'               => $this->num($row, 42),
                            'ppn'               => $this->num($row, 43),
                            'fix_exc_ppn'       => $this->num($row, 44),
                            'margin'            => $this->num($row, 45),
                            'percent_margin'    => $this->num($row, 46),
                            // REVISI: Menggunakan order_no (string)
                            'order_no'          => $this->val($row, 47), 
                            'supplier'          => $this->val($row, 48),
                            'mother_sku'        => $this->val($row, 49),
                            'last_supplier'     => $this->val($row, 50),
                            'divisi'            => $this->val($row, 51),
                            'unique_id'         => $this->val($row, 52),
                        ]
                    );
                    
                    $stats['imported']++;

                    if ($stats['total_rows'] % 500 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    Log::error("Skip Baris ke-" . json_encode($index) . " (Cabang: " . $cabangRaw . " SKU: " . $skuRaw . ") Error: " . $e->getMessage());
                    continue; 
                }
            }

            DB::commit();
            return $stats;

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Service Import Fatal: ' . $e->getMessage());
            throw $e;
        }
    }

    // --- HELPER SAKTI (ANTI ERROR) ---

    private function val($row, $index)
    {
        if (!isset($row[$index])) return '';
        $value = $row[$index];
        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        return trim((string)$value);
    }

    /**
     * Helper Angka: Memaksa jadi float, membersihkan kurung (), dan memastikan 2 desimal.
     */
    private function num($row, $index)
    {
        $val = $this->val($row, $index);
        
        if ($val === '' || $val === '-') return 0.00;
        
        $is_negative = false;
        
        // 1. Cek Kurung (Nilai Negatif)
        if (str_starts_with($val, '(') && str_ends_with($val, ')')) {
            $is_negative = true;
            $val = trim($val, '()');
        }

        // 2. Bersihkan karakter non-angka/desimal
        $clean = preg_replace('/[^0-9.,-]/', '', $val);
        
        // 3. Logika Desimal Indo vs Inggris
        if (strpos($clean, ',') !== false && strpos($clean, '.') === false) {
             // Cth: 100,50 -> 100.50
             $clean = str_replace(',', '.', $clean);
        } elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
             // Cth: 1.000,50 -> 1000.50
             $clean = str_replace('.', '', $clean);
             $clean = str_replace(',', '.', $clean);
        }
        
        $number = (float) $clean;
        
        // 4. Terapkan Negatif jika ada kurung
        if ($is_negative) {
            $number = $number * -1;
        }

        // 5. Kembalikan dengan format 2 desimal (penting untuk konsistensi database)
        return round($number, 2);
    }

    private function date($row, $index)
    {
        $val = $this->val($row, $index);
        
        $val = trim(str_replace("'", "", $val));

        if ($val === '' || $val === 'Blank' || $val === '-') return null;

        try {
            if (is_numeric($val)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d');
            }
            return Carbon::parse($val)->format('Y-m-d');
        } catch (Throwable $e) {
            return null;
        }
    }
}