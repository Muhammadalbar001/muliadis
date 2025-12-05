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
                    
                    // Helper Akses Data
                    $val = function ($i) use ($row) {
                        return $this->cleanStr($row, $i);
                    };
                    
                    $cabangRaw  = $val(0); 
                    $transNoRaw = $val(1); 
                    $kodeItem   = $val(8);

                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue; 

                    if ($transNoRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // --- PARSING TANGGAL CERDAS ---
                    // Menggunakan parseDateLoose yang sudah support Indo
                    $tglPenjualan = $this->parseDateLoose($row, 3);
                    $jatuhTempo   = $this->parseDateLoose($row, 5);
                    $ed           = $this->parseDateLoose($row, 11);

                    // 3. SIMPAN DATA
                    Penjualan::updateOrCreate(
                        [
                            'cabang'    => $cabangRaw ?: null,
                            'trans_no'  => $transNoRaw,
                            'kode_item' => $kodeItem
                        ],
                        [
                            'status'                => $val(2),
                            'tgl_penjualan'         => $tglPenjualan, // SUDAH FIXED
                            'period'                => $val(4), 
                            'jatuh_tempo'           => $jatuhTempo,
                            'kode_pelanggan'        => $val(6),
                            'nama_pelanggan'        => $val(7),
                            'sku'                   => $val(9),
                            'no_batch'              => $val(10),
                            'ed'                    => $ed,
                            'nama_item'             => $val(12),
                            
                            'qty'                   => $this->numSafe($row, 13),
                            'satuan_jual'           => $val(14),
                            'qty_i'                 => $this->numSafe($row, 15),
                            'satuan_i'              => $val(16),
                            'nilai'                 => $this->numSafe($row, 17),
                            'rata2'                 => $this->numSafe($row, 18),
                            'up_percent'            => $this->numSafe($row, 19),
                            'nilai_up'              => $this->numSafe($row, 20),
                            'nilai_jual_pembulatan' => $this->numSafe($row, 21),
                            
                            'd1'                    => $this->numSafe($row, 22),
                            'd2'                    => $this->numSafe($row, 23),
                            'diskon_1'              => $this->numSafe($row, 24),
                            'diskon_2'              => $this->numSafe($row, 25),
                            'diskon_bawah'          => $this->numSafe($row, 26),
                            'total_diskon'          => $this->numSafe($row, 27),
                            
                            'nilai_jual_net'        => $this->numSafe($row, 28),
                            'total_harga_jual'      => $this->numSafe($row, 29),
                            'ppn_head'              => $this->numSafe($row, 30),
                            'total_grand'           => $this->numSafe($row, 31),
                            'ppn_value'             => $this->numSafe($row, 32),
                            'total_min_ppn'         => $this->numSafe($row, 33),
                            'margin'                => $this->numSafe($row, 34),
                            
                            'pembayaran'            => $val(35),
                            'cash_bank'             => $val(36),
                            'kode_sales'            => $val(37),
                            'sales_name'            => $val(38),
                            'supplier'              => $val(39),
                            'status_pay'            => $val(40),
                            'trx_id'                => $val(41),
                            'year'                  => $this->numSafe($row, 42),
                            'month'                 => $this->numSafe($row, 43),
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

                    if ($stats['total_rows'] % 200 === 0) {
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
            Log::error('Import Fatal Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    // --- HELPER SAKTI ---

    private function cleanStr($row, $index)
    {
        if (!isset($row[$index])) return '';
        $val = $row[$index];
        
        if (is_object($val) || is_array($val)) {
            if ($val instanceof \DateTimeInterface) {
                return $val->format('Y-m-d');
            }
            return json_encode($val);
        }

        $str = trim((string)$val);
        // Hapus tanda kutip tunggal dan karakter invisible
        $str = str_replace("'", "", $str);
        $str = preg_replace('/[\x00-\x1F\x7F]/u', '', $str); // Hapus hidden chars
        
        return $str;
    }

    // Helper Parsing Tanggal Lebih Pintar
    private function parseDateLoose($row, $index)
    {
        $val = $this->cleanStr($row, $index);
        
        // Cek kosong atau strip saja
        if ($val === '' || $val === '-' || $val === 'Blank' || $val === '0') return null;

        try {
            // 1. Cek jika Excel Serial Number (Angka)
            if (is_numeric($val)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d');
            }

            // 2. Terjemahkan Bulan Indo ke Inggris (Januari -> January)
            $val = $this->translateDateIndo($val);

            // 3. Coba Parse dengan Carbon
            return Carbon::parse($val)->format('Y-m-d');

        } catch (Throwable $e) {
            // 4. Fallback: Coba format d/m/Y manual jika Carbon gagal
            try {
                return Carbon::createFromFormat('d/m/Y', $val)->format('Y-m-d');
            } catch (Throwable $ex) {
                return null; // Menyerah, kembalikan null agar tidak error fatal
            }
        }
    }

    private function translateDateIndo($dateStr)
    {
        $map = [
            'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
            'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
            'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
            'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December',
            'januari' => 'January', 'februari' => 'February', 'maret' => 'March',
            'april' => 'April', 'mei' => 'May', 'juni' => 'June',
            'juli' => 'July', 'agustus' => 'August', 'september' => 'September',
            'oktober' => 'October', 'november' => 'November', 'desember' => 'December',
            'Agust' => 'August', 'Okt' => 'October', 'Nop' => 'November', 'Des' => 'December'
        ];
        
        return strtr($dateStr, $map);
    }

    private function numSafe($row, $index)
    {
        $val = $this->cleanStr($row, $index);
        
        if ($val === '' || $val === '-' || $val === '0') return '0';
        
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