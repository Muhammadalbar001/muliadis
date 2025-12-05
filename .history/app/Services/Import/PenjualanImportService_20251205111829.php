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
        
        // Matikan log query Laravel agar RAM tidak meledak
        DB::disableQueryLog();

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();
            
            $stats = [
                'total_rows' => 0,
                'imported' => 0,
                'skipped_empty' => 0,
                'skipped_error' => 0,
            ];

            // Tampungan Batch
            $batchData = [];
            $batchSize = 2000; // Kirim 2000 data sekaligus (Optimal untuk 350rb baris)
            $now = now(); // Timestamp untuk created_at/updated_at

            // Kita pakai lazy collection dari Spatie agar hemat RAM
            foreach ($reader->getRows() as $index => $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values((array)$rawRow);
                    
                    // Helper Cepat
                    $val = function ($i) use ($row) {
                        return $this->cleanStr($row, $i);
                    };
                    
                    $cabangRaw  = $val(0); 
                    $transNoRaw = $val(1); 
                    $kodeItem   = $val(8); 

                    // Validasi Dasar
                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue; 
                    if ($transNoRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // Parsing Tanggal
                    $tglPenjualan = $this->parseDateLoose($row, 3);
                    $jatuhTempo   = $this->parseDateLoose($row, 5);
                    $ed           = $this->parseDateLoose($row, 11);

                    // --- MASUKKAN KE ARRAY BATCH (Bukan langsung DB) ---
                    $batchData[] = [
                        // Kunci Unik
                        'cabang'            => $cabangRaw ?: null,
                        'trans_no'          => $transNoRaw,
                        'kode_item'         => $kodeItem,

                        // Data Lain
                        'status'            => $val(2),
                        'tgl_penjualan'     => $tglPenjualan,
                        'period'            => $val(4),
                        'jatuh_tempo'       => $jatuhTempo,
                        'kode_pelanggan'    => $val(6),
                        'nama_pelanggan'    => $val(7),
                        'sku'               => $val(9),
                        'no_batch'          => $val(10),
                        'ed'                => $ed,
                        'nama_item'         => $val(12),
                        
                        'qty'               => $this->numSafe($row, 13),
                        'satuan_jual'       => $val(14),
                        'qty_i'             => $this->numSafe($row, 15),
                        'satuan_i'          => $val(16),
                        'nilai'             => $this->numSafe($row, 17),
                        'rata2'             => $this->numSafe($row, 18),
                        'up_percent'        => $this->numSafe($row, 19),
                        'nilai_up'          => $this->numSafe($row, 20),
                        'nilai_jual_pembulatan' => $this->numSafe($row, 21),
                        
                        'd1'                => $this->numSafe($row, 22),
                        'd2'                => $this->numSafe($row, 23),
                        'diskon_1'          => $this->numSafe($row, 24),
                        'diskon_2'          => $this->numSafe($row, 25),
                        'diskon_bawah'      => $this->numSafe($row, 26),
                        'total_diskon'      => $this->numSafe($row, 27),
                        
                        'nilai_jual_net'    => $this->numSafe($row, 28),
                        'total_harga_jual'  => $this->numSafe($row, 29),
                        'ppn_head'          => $this->numSafe($row, 30),
                        'total_grand'       => $this->numSafe($row, 31),
                        'ppn_value'         => $this->numSafe($row, 32),
                        'total_min_ppn'     => $this->numSafe($row, 33),
                        'margin'            => $this->numSafe($row, 34),
                        
                        'pembayaran'        => $val(35),
                        'cash_bank'         => $val(36),
                        'kode_sales'        => $val(37),
                        'sales_name'        => $val(38),
                        'supplier'          => $val(39),
                        'status_pay'        => $val(40),
                        'trx_id'            => $val(41),
                        'year'              => $this->numSafe($row, 42),
                        'month'             => $this->numSafe($row, 43),
                        
                        'last_suppliers'         => $val(44),
                        'mother_sku'             => $val(45),
                        'divisi'                 => $val(46),
                        'program'                => $val(47),
                        'outlet_code_sales_name' => $val(48),
                        'city_code_outlet_program' => $val(49),
                        'sales_name_outlet_code'   => $val(50),

                        // Timestamp (Wajib manual di insert massal)
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];

                    // --- EKSEKUSI JIKA SUDAH 2000 BARIS ---
                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['imported'] += count($batchData);
                        $batchData = []; // Kosongkan memori
                        
                        // Garbage Collector (Opsional, membantu RAM)
                        if ($stats['imported'] % 10000 === 0) gc_collect_cycles();
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    // Log error sesekali saja agar log file tidak bengkak
                    if ($stats['skipped_error'] <= 10) { 
                        Log::error("Skip Penjualan Baris ke-{$index}: " . $e->getMessage());
                    }
                    continue; 
                }
            }

            // --- EKSEKUSI SISA DATA TERAKHIR ---
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
     * FUNGSI UPSERT MASSAL (KUNCI KECEPATAN)
     */
    private function processBatch(array $data)
    {
        // upsert(data, unique_keys, columns_to_update)
        // Unique Key harus sesuai dengan migrasi unique(['cabang', 'trans_no', 'kode_item'])
        
        Penjualan::upsert(
            $data, 
            ['cabang', 'trans_no', 'kode_item'], // Kunci Unik untuk deteksi duplikat
            [
                // Kolom yang di-update jika data sudah ada
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
    
    // --- HELPERS ---

    private function cleanStr($row, $index)
    {
        if (!isset($row[$index])) return '';
        $val = $row[$index];
        if (is_object($val) || is_array($val)) {
            if ($val instanceof \DateTimeInterface) return $val->format('Y-m-d');
            return json_encode($val);
        }
        return trim(str_replace("'", "", (string)$val));
    }

    private function parseDateLoose($row, $index)
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
            'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March', 'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
            'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September', 'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December',
            'Agust' => 'August', 'Okt' => 'October', 'Nop' => 'November', 'Des' => 'December'
        ];
        return strtr($dateStr, $map);
    }

    private function numSafe($row, $index): string
    {
        $val = $this->cleanStr($row, $index);
        if ($val === '' || $val === '0' || $val === '-') return '0';
        
        if (str_starts_with($val, '(') && str_ends_with($val, ')')) {
            $number_part = trim($val, '()');
            $clean = preg_replace('/[^0-9.,-]/', '', $number_part);
            if (strpos($clean, ',') !== false && strpos($clean, '.') === false) $clean = str_replace(',', '.', $clean);
            elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) { $clean = str_replace('.', '', $clean); $clean = str_replace(',', '.', $clean); }
            return '-' . number_format((float)$clean, 2, '.', '');
        }
        return $val;
    }
}