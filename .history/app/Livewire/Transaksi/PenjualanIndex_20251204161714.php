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
                    
                    // --- HELPER LOKAL (CLEAN QUOTES) ---
                    // Helper ini otomatis menghapus tanda kutip (') dari data
                    $val = function ($r, $i) {
                        return $this->valClean($r, $i); 
                    };
                    
                    $cabangRaw = $val($row, 0); 
                    $transNoRaw = $val($row, 1); 
                    $kodeItem = $val($row, 8); 

                    // Skip Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0) {
                        continue; 
                    }

                    // 1. Validasi Kunci Utama
                    if ($transNoRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // 2. Validasi Tanggal (Penting!)
                    $tglPenjualan = $this->date($row, 3);
                    
                    // Jika tanggal penjualan gagal diparse, jangan langsung error, coba ambil raw stringnya jika formatnya YYYY-MM-DD
                    if (is_null($tglPenjualan)) {
                        // Cek manual stringnya
                        $rawTgl = $val($row, 3);
                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawTgl)) {
                            $tglPenjualan = $rawTgl;
                        } else {
                            throw new \Exception("Tanggal Penjualan (Kolom D) invalid: " . $rawTgl);
                        }
                    }

                    // 3. SIMPAN DATA
                    Penjualan::updateOrCreate(
                        [
                            'cabang'    => $cabangRaw ?: null,
                            'trans_no'  => $transNoRaw,
                            'kode_item' => $kodeItem
                        ],
                        [
                            'status'                => $val($row, 2),
                            'tgl_penjualan'         => $tglPenjualan, 
                            
                            // PERBAIKAN: Period dibersihkan dari kutip
                            'period'                => $val($row, 4),
                            
                            // PERBAIKAN: Jatuh Tempo dibersihkan dari kutip
                            'jatuh_tempo'           => $this->date($row, 5),
                            
                            'kode_pelanggan'        => $val($row, 6),
                            'nama_pelanggan'        => $val($row, 7),
                            'sku'                   => $val($row, 9),
                            'no_batch'              => $val($row, 10),
                            'ed'                    => $this->date($row, 11),
                            'nama_item'             => $val($row, 12),
                            
                            // ANGKA
                            'qty'                   => $this->numSafe($row, 13),
                            'satuan_jual'           => $val($row, 14),
                            'qty_i'                 => $this->numSafe($row, 15),
                            'satuan_i'              => $val($row, 16),
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
                            
                            // TOTAL
                            'nilai_jual_net'        => $this->numSafe($row, 28),
                            'total_harga_jual'      => $this->numSafe($row, 29),
                            'ppn_head'              => $this->numSafe($row, 30),
                            'total_grand'           => $this->numSafe($row, 31),
                            'ppn_value'             => $this->numSafe($row, 32),
                            'total_min_ppn'         => $this->numSafe($row, 33),
                            'margin'                => $this->numSafe($row, 34),
                            
                            // META
                            'pembayaran'            => $val($row, 35),
                            'cash_bank'             => $val($row, 36),
                            'kode_sales'            => $val($row, 37),
                            'sales_name'            => $val($row, 38),
                            'supplier'              => $val($row, 39),
                            'status_pay'            => $val($row, 40),
                            'trx_id'                => $val($row, 41),
                            'year'                  => $this->numSafe($row, 42),
                            'month'                 => $this->numSafe($row, 43),
                            'last_suppliers'        => $val($row, 44),
                            'mother_sku'            => $val($row, 45),
                            'divisi'                => $val($row, 46),
                            'program'               => $val($row, 47),
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
                    Log::error("Skip Penjualan Baris ke-" . json_encode($index) . " Error: " . $e->getMessage());
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
    
    // --- HELPERS BARU ---

    // 1. Helper Val Clean: Mengambil string dan MENGHAPUS KUTIP (')
    private function valClean($row, $index)
    {
        if (!isset($row[$index])) return '';
        $value = $row[$index];

        // Jika Excel mengembalikan Objek Tanggal, ubah ke string
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        
        $str = trim(strval($value));
        // Hapus tanda kutip tunggal dimanapun berada
        return str_replace("'", "", $str);
    }

    // 2. Helper Date: Membersihkan tanda kutip (') lalu parse
    private function date($row, $index)
    {
        // Gunakan valClean agar kutip hilang duluan
        $val = $this->valClean($row, $index);

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

    // 3. Helper NumSafe: Menjaga "-" tetap "-"
    private function numSafe($row, $index): string
    {
        // Gunakan valClean
        $val = $this->valClean($row, $index);

        if ($val === '-') {
            return '-'; 
        }
        if (empty($val) || $val === '0') {
            return '0';
        }
        
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
}