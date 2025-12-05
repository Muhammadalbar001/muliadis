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
                    
                    // --- HELPER LOKAL ---
                    $val = function ($i) use ($row) {
                        return $this->val($row, $i);
                    };
                    
                    // Ambil Data Kunci
                    $cabangRaw  = $val(0); // Col A
                    $transNoRaw = $val(1); // Col B
                    $kodeItem   = $val(8); // Col I (PENTING: Kode Item sebagai pembeda baris)

                    // 1. Skip Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0) {
                        continue; 
                    }

                    // 2. Skip Baris Kosong (Wajib ada Trans No & Kode Item)
                    if ($transNoRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // 3. Simpan ke Database
                    Penjualan::updateOrCreate(
                        [
                            // KUNCI GABUNGAN (Composite Key)
                            'cabang'    => $cabangRaw ?: null,
                            'trans_no'  => $transNoRaw,
                            'kode_item' => $kodeItem 
                        ],
                        [
                            // MAPPING SESUAI URUTAN EXCEL ANDA (0 - 50)
                            'status'                => $val(2),
                            'tgl_penjualan'         => $this->date($row, 3), // Col D
                            'period'                => $val(4),              // Col E
                            'jatuh_tempo'           => $this->date($row, 5), // Col F (Ada tanda ')
                            'kode_pelanggan'        => $val(6),
                            'nama_pelanggan'        => $val(7),
                            // Kode Item (8) ada di kunci
                            'sku'                   => $val(9),
                            'no_batch'              => $val(10),
                            'ed'                    => $this->date($row, 11), // Col L
                            'nama_item'             => $val(12),
                            
                            // QTY & NILAI
                            'qty'                   => $this->numSafe($row, 13),
                            'satuan_jual'           => $val(14),
                            'qty_i'                 => $this->numSafe($row, 15),
                            'satuan_i'              => $val(16),
                            'nilai'                 => $this->numSafe($row, 17),
                            'rata2'                 => $this->numSafe($row, 18),
                            'up_percent'            => $this->numSafe($row, 19),
                            'nilai_up'              => $this->numSafe($row, 20),
                            'nilai_jual_pembulatan' => $this->numSafe($row, 21),
                            
                            // DISKON
                            'd1'                    => $this->numSafe($row, 22),
                            'd2'                    => $this->numSafe($row, 23),
                            'diskon_1'              => $this->numSafe($row, 24),
                            'diskon_2'              => $this->numSafe($row, 25),
                            'diskon_bawah'          => $this->numSafe($row, 26),
                            'total_diskon'          => $this->numSafe($row, 27),
                            
                            // TOTAL & MARGIN
                            'nilai_jual_net'        => $this->numSafe($row, 28),
                            'total_harga_jual'      => $this->numSafe($row, 29),
                            'ppn_head'              => $this->numSafe($row, 30),
                            'total_grand'           => $this->numSafe($row, 31),
                            'ppn_value'             => $this->numSafe($row, 32),
                            'total_min_ppn'         => $this->numSafe($row, 33),
                            'margin'                => $this->numSafe($row, 34),
                            
                            // PAYMENT
                            'pembayaran'            => $val(35),
                            'cash_bank'             => $val(36),
                            'kode_sales'            => $val(37),
                            'sales_name'            => $val(38),
                            'supplier'              => $val(39),
                            'status_pay'            => $val(40),
                            'trx_id'                => $val(41),
                            'year'                  => $this->numSafe($row, 42),
                            'month'                 => $this->numSafe($row, 43),
                            
                            // META LAINNYA
                            'last_suppliers'        => $val(44),
                            'mother_sku'            => $val(45),
                            'divisi'                => $val(46),
                            'program'               => $val(47),
                            'outlet_code_sales_name'=> $val(48),
                            'city_code_outlet_program' => $val(49),
                            'sales_name_outlet_code' => $val(50),
                        ]
                    );
                    
                    $stats['imported']++;

                    if ($stats['total_rows'] % 500 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    Log::error("Skip Penjualan Baris ke-{$index}: " . $e->getMessage());
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
    
    // --- HELPER SAKTI ---

    private function val($row, $index)
    {
        if (!isset($row[$index])) return '';
        $value = $row[$index];
        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        return trim(strval($value));
    }

    private function numSafe($row, $index): string
    {
        $val = $this->val($row, $index);
        
        // Hapus kutip jika ada (misal: '500)
        $val = ltrim($val, "'");
        $val = trim($val);

        if ($val === '' || $val === '0' || $val === '-') {
            return $val; // Kembalikan apa adanya (0 atau -)
        }
        
        // Handle kurung negatif: (500) -> -500.00
        // Ini penting agar bisa dihitung SQL (SUM, AVG) nanti
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
        
        // Hapus tanda kutip (') di awal string
        $val = ltrim($val, "'");
        $val = trim($val);

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