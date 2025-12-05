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
                    
                    // Gunakan Array Casting dan Helper Aman
                    $row = array_values((array)$rawRow);

                    $cabangRaw = $this->val($row, 0); 
                    $skuRaw    = $this->val($row, 2); 

                    if (strcasecmp($cabangRaw, 'cabang') === 0) { continue; }
                    if ($skuRaw === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // 3. SIMPAN DATA (Semua input angka sekarang adalah STRING dari helper numSafe)
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
                            'stok'              => $this->numSafe($row, 6), // OUTPUT STRING
                            'oum'               => $this->val($row, 7),
                            'good'              => $this->numSafe($row, 8), // OUTPUT STRING
                            'good_konversi'     => $this->val($row, 9),
                            'ktn'               => $this->numSafe($row, 10), // OUTPUT STRING
                            'good_amount'       => $this->numSafe($row, 11), // OUTPUT STRING
                            'avg_3m_in_oum'     => $this->numSafe($row, 12),
                            'avg_3m_in_ktn'     => $this->numSafe($row, 13),
                            'avg_3m_in_value'   => $this->numSafe($row, 14),
                            'not_move_3m'       => $this->val($row, 15),
                            'bad'               => $this->numSafe($row, 16),
                            'bad_konversi'      => $this->val($row, 17),
                            'bad_ktn'           => $this->numSafe($row, 18),
                            'bad_amount'        => $this->numSafe($row, 19),
                            'wrh1'              => $this->numSafe($row, 20),
                            'wrh1_konversi'     => $this->val($row, 21),
                            'wrh1_amount'       => $this->numSafe($row, 22),
                            'wrh2'              => $this->numSafe($row, 23),
                            'wrh2_konversi'     => $this->val($row, 24),
                            'wrh2_amount'       => $this->numSafe($row, 25),
                            'wrh3'              => $this->numSafe($row, 26),
                            'wrh3_konversi'     => $this->val($row, 27),
                            'wrh3_amount'       => $this->numSafe($row, 28),
                            'good_storage'      => $this->val($row, 29),
                            'sell_per_week'     => $this->numSafe($row, 30),
                            'blank_field'       => $this->val($row, 31),
                            'empty_field'       => $this->val($row, 32),
                            'min'               => $this->numSafe($row, 33),
                            're_qty'            => $this->numSafe($row, 34),
                            'expired_info'      => $this->date($row, 35),
                            'buy'               => $this->numSafe($row, 36),
                            'buy_disc'          => $this->numSafe($row, 37),
                            'buy_in_ktn'        => $this->numSafe($row, 38),
                            'avg'               => $this->numSafe($row, 39),
                            'total'             => $this->numSafe($row, 40),
                            'up'                => $this->numSafe($row, 41),
                            'fix'               => $this->numSafe($row, 42),
                            'ppn'               => $this->numSafe($row, 43),
                            'fix_exc_ppn'       => $this->numSafe($row, 44),
                            'margin'            => $this->numSafe($row, 45),
                            'percent_margin'    => $this->numSafe($row, 46),
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
                    Log::error("Skip Baris ke-" . json_encode($index) . " Error: " . $e->getMessage());
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

    // --- HELPER SAKTI (OUTPUT STRING MURNI DARI EXCEL) ---

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
     * Helper Angka/Nilai yang mengembalikan STRING murni dari Excel.
     * Mengubah format kurung (negatif) menjadi minus (-) dan mengembalikan "-" jika inputnya "-".
     */
    private function numSafe($row, $index): string
    {
        $val = $this->val($row, $index);
        $val = trim($val);

        if (empty($val) || $val === '0') {
            return '0';
        }

        // Cek jika nilainya TANDA MINUS MURNI (sesuai permintaan user: tampilkan '-')
        if ($val === '-') {
            return '-';
        }
        
        // Cek Kurung (Nilai Negatif)
        if (str_starts_with($val, '(') && str_ends_with($val, ')')) {
            $number_part = trim($val, '()');
            
            // Konversi ke float untuk normalisasi koma/titik, lalu kembalikan string negatif
            $normalized_float = $this->cleanAndNormalizeNumber($number_part);
            
            // Format ulang dengan 2 desimal (misal: -168015805849976.00)
            return '-' . number_format($normalized_float, 2, '.', '');
        }

        // Jika bukan negatif kurung, hanya bersihkan spasi dan kembalikan string murni
        // Kita tidak format di sini agar jika inputnya '2' tetap '2'
        return $val;
    }
    
    /**
     * Membersihkan dan menormalisasi format angka (digunakan internal oleh numSafe)
     */
    private function cleanAndNormalizeNumber($val): float
    {
        // Hanya ambil angka, titik, koma, minus
        $clean = preg_replace('/[^0-9.,-]/', '', $val);
        
        // Logika Desimal Indo vs Inggris
        if (strpos($clean, ',') !== false && strpos($clean, '.') === false) {
             $clean = str_replace(',', '.', $clean);
        } elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
             $clean = str_replace('.', '', $clean);
             $clean = str_replace(',', '.', $clean);
        }
        return (float) $clean;
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