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
        // 1. SETTING SUPER POWER
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        
        // Matikan query log agar RAM tidak bocor
        DB::disableQueryLog();

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();
            
            $stats = [
                'total_rows' => 0,
                'imported' => 0,
                'skipped_empty' => 0,
                'skipped_error' => 0,
            ];

            $batchData = [];
            $batchSize = 1000; // Kirim per 1000 baris (Jauh lebih cepat)

            foreach ($reader->getRows() as $index => $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values((array)$rawRow);
                    
                    // --- HELPER LOKAL CEPAT ---
                    $val = function ($i) use ($row) {
                        return isset($row[$i]) ? trim(str_replace("'", "", (string)$row[$i])) : '';
                    };
                    
                    $cabangRaw  = $val(0); 
                    $transNoRaw = $val(1); 
                    $kodeItem   = $val(8); 

                    // Skip Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue; 

                    // Skip Empty
                    if ($transNoRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // Validasi Tanggal (Cepat)
                    $tglPenjualan = $this->date($row, 3);
                    if (is_null($tglPenjualan)) {
                        // Coba fallback manual YYYY-MM-DD
                        $rawTgl = $val(3);
                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawTgl)) {
                            $tglPenjualan = $rawTgl;
                        } else {
                            // Jika tanggal invalid, skip baris ini agar tidak merusak batch
                            $stats['skipped_error']++;
                            continue;
                        }
                    }

                    // --- SIAPKAN DATA UNTUK BATCH (ARRAY MURNI) ---
                    // Kita tidak pakai Model::create disini, tapi array biasa
                    $batchData[] = [
                        'cabang'    => $cabangRaw,
                        'trans_no'  => $transNoRaw,
                        'kode_item' => $kodeItem,
                        
                        'status'                => $val(2),
                        'tgl_penjualan'         => $tglPenjualan,
                        'period'                => $val(4),
                        'jatuh_tempo'           => $this->date($row, 5),
                        'kode_pelanggan'        => $val(6),
                        'nama_pelanggan'        => $val(7),
                        'sku'                   => $val(9),
                        'no_batch'              => $val(10),
                        'ed'                    => $this->date($row, 11),
                        'nama_item'             => $val(12),
                        
                        // ANGKA
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
                        
                        // TOTAL
                        'nilai_jual_net'        => $this->numSafe($row, 28),
                        'total_harga_jual'      => $this->numSafe($row, 29),
                        'ppn_head'              => $this->numSafe($row, 30),
                        'total_grand'           => $this->numSafe($row, 31),
                        'ppn_value'             => $this->numSafe($row, 32),
                        'total_min_ppn'         => $this->numSafe($row, 33),
                        'margin'                => $this->numSafe($row, 34),
                        
                        // META
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
                        
                        // Wajib diisi manual untuk upsert
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ];

                    // --- EKSEKUSI BATCH JIKA SUDAH 1000 ---
                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['imported'] += count($batchData);
                        $batchData = []; // Kosongkan RAM
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    Log::error("Skip Baris {$index}: " . $e->getMessage());
                    continue; 
                }
            }

            // --- EKSEKUSI SISA DATA ---
            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['imported'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error('Import Fatal Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * PROSES BATCH UPSERT (KUNCI KECEPATAN)
     */
    private function processBatch(array $batchData)
    {
        // Upsert: Masukkan data, jika (cabang, trans_no, kode_item) sama, maka UPDATE kolom lainnya.
        Penjualan::upsert(
            $batchData, 
            ['cabang', 'trans_no', 'kode_item'], // Kunci Unik (Harus sesuai Migration step 1)
            [
                'status', 'tgl_penjualan', 'period', 'jatuh_tempo', 
                'kode_pelanggan', 'nama_pelanggan', 'sku', 'no_batch', 'ed', 'nama_item',
                'qty', 'satuan_jual', 'qty_i', 'satuan_i', 
                'nilai', 'rata2', 'up_percent', 'nilai_up', 'nilai_jual_pembulatan',
                'd1', 'd2', 'diskon_1', 'diskon_2', 'diskon_bawah', 'total_diskon',
                'nilai_jual_net', 'total_harga_jual', 'ppn_head', 'total_grand', 'ppn_value', 'total_min_ppn', 'margin',
                'pembayaran', 'cash_bank', 'kode_sales', 'sales_name', 'supplier', 'status_pay', 'trx_id',
                'year', 'month', 'last_suppliers', 'mother_sku', 'divisi', 'program',
                'outlet_code_sales_name', 'city_code_outlet_program', 'sales_name_outlet_code',
                'updated_at'
            ]
        );
    }
    
    // --- HELPER SAKTI (Optimized) ---

    private function cleanStr($row, $index)
    {
        if (!isset($row[$index])) return '';
        $val = $row[$index];
        
        if (is_object($val) || is_array($val)) {
            if ($val instanceof \DateTimeInterface) return $val->format('Y-m-d');
            return ''; // Abaikan array, kembalikan kosong biar gak error
        }

        return trim(str_replace("'", "", (string)$val));
    }

    private function date($row, $index)
    {
        $val = $this->cleanStr($row, $index);
        if ($val === '' || $val === '-' || $val === 'Blank') return null;

        try {
            if (is_numeric($val)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d');
            }
            $val = $this->translateDateIndo($val);
            return Carbon::parse($val)->format('Y-m-d');
        } catch (Throwable $e) {
            return null;
        }
    }

    private function translateDateIndo($dateStr)
    {
        $map = [
            'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
            'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
            'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
            'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December',
            'Agust' => 'August', 'Okt' => 'October', 'Nop' => 'November', 'Des' => 'December'
        ];
        return strtr($dateStr, $map);
    }

    private function numSafe($row, $index): string
    {
        $val = $this->cleanStr($row, $index);
        if ($val === '' || $val === '0' || $val === '-') return $val ?: '0';
        
        // Handle kurung (100) -> -100.00
        if (str_starts_with($val, '(') && str_ends_with($val, ')')) {
            $number_part = trim($val, '()');
            $clean = preg_replace('/[^0-9.,-]/', '', $number_part);
            
            // Normalize Decimal
            if (strpos($clean, ',') !== false && strpos($clean, '.') === false) {
                 $clean = str_replace(',', '.', $clean);
            } elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
                 $clean = str_replace('.', '', $clean);
                 $clean = str_replace(',', '.', $clean);
            }
            
            return '-' . number_format((float)$clean, 2, '.', '');
        }

        return $val;
    }
}