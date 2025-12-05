<?php

namespace App\Services\Import;

use App\Models\Transaksi\Penjualan;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class PenjualanImportService
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
                    $row = array_values((array)$rawRow);
                    
                    // Helper aman dari Array to String
                    $val = function ($r, $i) {
                        if (!isset($r[$i])) return '';
                        $value = $r[$i];
                        if (is_array($value) || is_object($value)) {
                            return json_encode($value, JSON_UNESCAPED_UNICODE);
                        }
                        return trim(strval($value));
                    };
                    
                    $cabangRaw = $val($row, 0); // Cabang (Index 0)
                    $transNoRaw = $val($row, 1); // Trans No (Index 1)
                    $kodeItem = $val($row, 8); // Kode Item (Index 8)

                    // Skip Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0) {
                        continue; 
                    }

                    // Skip Empty Trans No / Kode Item (Kunci utama)
                    if ($transNoRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // 3. SIMPAN DATA (Update Or Create)
                    Penjualan::updateOrCreate(
                        [
                            // KUNCI GABUNGAN: Untuk memastikan setiap item di setiap faktur di setiap cabang masuk
                            'cabang'    => $cabangRaw ?: null,
                            'trans_no'  => $transNoRaw,
                            'kode_item' => $kodeItem
                        ],
                        [
                            'status'                => $val($row, 2),
                            'tgl_penjualan'         => $this->date($row, 3), // Penjualan (Tanggal)
                            'period'                => $val($row, 4),
                            'jatuh_tempo'           => $this->date($row, 5),
                            'kode_pelanggan'        => $val($row, 6),
                            'nama_pelanggan'        => $val($row, 7),
                            // Kode Item ada di kunci
                            'sku'                   => $val($row, 9),
                            'no_batch'              => $val($row, 10),
                            'ed'                    => $this->date($row, 11),
                            'nama_item'             => $val($row, 12),
                            
                            // QTY & UNIT (STRING)
                            'qty'                   => $this->numSafe($row, 13),
                            'satuan_jual'           => $val($row, 14),
                            'qty_i'                 => $this->numSafe($row, 15),
                            'satuan_i'              => $val($row, 16),
                            
                            // NILAI (STRING)
                            'nilai'                 => $this->numSafe($row, 17),
                            'rata2'                 => $this->numSafe($row, 18),
                            'up_percent'            => $this->numSafe($row, 19),
                            'nilai_up'              => $this->numSafe($row, 20),
                            'nilai_jual_pembulatan' => $this->numSafe($row, 21),
                            
                            // DISKON (STRING)
                            'd1'                    => $this->numSafe($row, 22),
                            'd2'                    => $this->numSafe($row, 23),
                            'diskon_1'              => $this->numSafe($row, 24),
                            'diskon_2'              => $this->numSafe($row, 25),
                            'diskon_bawah'          => $this->numSafe($row, 26),
                            'total_diskon'          => $this->numSafe($row, 27),
                            
                            // TOTALS (STRING)
                            'nilai_jual_net'        => $this->numSafe($row, 28),
                            'total_harga_jual'      => $this->numSafe($row, 29),
                            'ppn_head'              => $this->numSafe($row, 30),
                            'total_grand'           => $this->numSafe($row, 31),
                            'ppn_value'             => $this->numSafe($row, 32),
                            'total_min_ppn'         => $this->numSafe($row, 33),
                            'margin'                => $this->numSafe($row, 34),
                            
                            // PAYMENT & SALES
                            'pembayaran'            => $val($row, 35),
                            'cash_bank'             => $val($row, 36),
                            'kode_sales'            => $val($row, 37),
                            'sales_name'            => $val($row, 38),
                            'supplier'              => $val($row, 39),
                            'status_pay'            => $val($row, 40),
                            'trx_id'                => $val($row, 41),
                            
                            // PERIOD & META
                            'year'                  => $this->numSafe($row, 42),
                            'month'                 => $this->numSafe($row, 43),
                            'last_suppliers'        => $val($row, 44),
                            'mother_sku'            => $val($row, 45),
                            'divisi'                => $val($row, 46),
                            'program'               => $val($row, 47),
                            
                            // GABUNGAN STRING
                            'outlet_code_sales_name'=> $val($row, 48),
                            'city_code_outlet_program' => $val($row, 49),
                            'sales_name_outlet_code' => $val($row, 50),
                        ]
                    );
                    
                    $stats['imported']++;

                    if ($stats['total_rows'] % 500 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    Log::error("Skip Penjualan Baris ke-" . json_encode($index) . " TransNo: " . $transNoRaw . " Error: " . $e->getMessage());
                    continue; 
                }
            }

            DB::commit();
            return $stats;

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Service Import Penjualan Fatal: ' . $e->getMessage());
            throw $e;
        }
    }
    
    // Helper untuk mengubah () menjadi minus jika diperlukan
    private function numSafe($row, $index): string
    {
        $val = $this->val($row, $index);
        $val = trim($val);

        if (empty($val) || $val === '0') {
            return '0';
        }
        if ($val === '-') {
            return '-';
        }
        
        // Handle kurung negatif (misalnya, (100) menjadi -100)
        if (str_starts_with($val, '(') && str_ends_with($val, ')')) {
            $number_part = trim($val, '()');
            $normalized_float = $this->cleanAndNormalizeNumber($number_part);
            return '-' . number_format($normalized_float, 2, '.', '');
        }

        return $val;
    }
    
    private function cleanAndNormalizeNumber($val): float
    {
        $clean = preg_replace('/[^0-9.,-]/', '', $val);
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
    
    // Helper aman dari Array to String
    private function val($row, $index) {
        if (!isset($row[$index])) return '';
        $value = $row[$index];
        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        return trim(strval($value));
    }
}