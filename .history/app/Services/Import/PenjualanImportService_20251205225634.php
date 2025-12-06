<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;

class PenjualanImportService
{
    public function handle(string $filePath)
    {
        // Konfigurasi PHP untuk proses berat
        ini_set('memory_limit', '1024M'); // Pastikan memory cukup
        ini_set('max_execution_time', 0);
        
        DB::disableQueryLog(); // Matikan log query Laravel untuk hemat memory

        try {
            // Gunakan header row jika ada untuk melewati baris pertama otomatis
            // Jika file excel punya header, sebaiknya hapus ->noHeaderRow() dan gunakan header mapping
            // Namun jika struktur flat/tanpa header konsisten, gunakan seperti di bawah:
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'     => 0,
                'imported'       => 0,
                'skipped_empty'  => 0,
                'skipped_error'  => 0,
            ];

            // NAIKKAN BATCH SIZE
            // 2000 -> 5000 (Mengurangi trip ke database dari 175x menjadi 70x)
            $batchSize = 5000; 
            $batchData = [];
            $now       = date('Y-m-d H:i:s');

            foreach ($reader->getRows() as $index => $rawRow) {
                try {
                    $stats['total_rows']++;
                    
                    // Mengambil nilai array secara langsung lebih cepat daripada casting (array) berulang
                    $row = array_values($rawRow);

                    // Ambil data kunci dulu untuk validasi cepat
                    $cabangRaw  = isset($row[0]) ? trim(str_replace("'", "", (string)$row[0])) : '';
                    
                    // Skip header manual (jika ada baris bertuliskan 'Cabang')
                    // Cek karakter pertama saja untuk lebih cepat daripada strcasecmp full string
                    if ($cabangRaw !== '' && ($cabangRaw[0] === 'C' || $cabangRaw[0] === 'c')) {
                        if (strcasecmp($cabangRaw, 'cabang') === 0) continue;
                    }

                    $transNoRaw = isset($row[1]) ? trim(str_replace("'", "", (string)$row[1])) : '';
                    $kodeItem   = isset($row[8]) ? trim(str_replace("'", "", (string)$row[8])) : '';

                    if ($transNoRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // Parsing tanggal
                    $tglPenjualan = $this->parseDateFast($row, 3);
                    $jatuhTempo   = $this->parseDateFast($row, 5);
                    $ed           = $this->parseDateFast($row, 11);

                    // BUILD DATA ROW - Panggil helper langsung tanpa closure
                    $batchData[] = [
                        'cabang'            => $cabangRaw ?: null,
                        'trans_no'          => $transNoRaw,
                        'kode_item'         => $kodeItem,
                        'status'            => $this->cleanStr($row, 2),
                        'tgl_penjualan'     => $tglPenjualan,
                        'period'            => $this->cleanStr($row, 4),
                        'jatuh_tempo'       => $jatuhTempo,
                        'kode_pelanggan'    => $this->cleanStr($row, 6),
                        'nama_pelanggan'    => $this->cleanStr($row, 7),
                        'sku'               => $this->cleanStr($row, 9),
                        'no_batch'          => $this->cleanStr($row, 10),
                        'ed'                => $ed,
                        'nama_item'         => $this->cleanStr($row, 12),

                        // Numeric conversions
                        'qty'                   => $this->numSafeFast($row, 13),
                        'satuan_jual'           => $this->cleanStr($row, 14),
                        'qty_i'                 => $this->numSafeFast($row, 15),
                        'satuan_i'              => $this->cleanStr($row, 16),
                        'nilai'                 => $this->numSafeFast($row, 17),
                        'rata2'                 => $this->numSafeFast($row, 18),
                        'up_percent'            => $this->numSafeFast($row, 19),
                        'nilai_up'              => $this->numSafeFast($row, 20),
                        'nilai_jual_pembulatan' => $this->numSafeFast($row, 21),
                        'd1'                    => $this->numSafeFast($row, 22),
                        'd2'                    => $this->numSafeFast($row, 23),
                        'diskon_1'              => $this->numSafeFast($row, 24),
                        'diskon_2'              => $this->numSafeFast($row, 25),
                        'diskon_bawah'          => $this->numSafeFast($row, 26),
                        'total_diskon'          => $this->numSafeFast($row, 27),
                        'nilai_jual_net'        => $this->numSafeFast($row, 28),
                        'total_harga_jual'      => $this->numSafeFast($row, 29),
                        'ppn_head'              => $this->numSafeFast($row, 30),
                        'total_grand'           => $this->numSafeFast($row, 31),
                        'ppn_value'             => $this->numSafeFast($row, 32),
                        'total_min_ppn'         => $this->numSafeFast($row, 33),
                        'margin'                => $this->numSafeFast($row, 34),

                        'pembayaran'        => $this->cleanStr($row, 35),
                        'cash_bank'         => $this->cleanStr($row, 36),
                        'kode_sales'        => $this->cleanStr($row, 37),
                        'sales_name'        => $this->cleanStr($row, 38),
                        'supplier'          => $this->cleanStr($row, 39),
                        'status_pay'        => $this->cleanStr($row, 40),
                        'trx_id'            => $this->cleanStr($row, 41),
                        'year'              => $this->numSafeFast($row, 42),
                        'month'             => $this->numSafeFast($row, 43),
                        'last_suppliers'    => $this->cleanStr($row, 44),
                        'mother_sku'        => $this->cleanStr($row, 45),
                        'divisi'            => $this->cleanStr($row, 46),
                        'program'           => $this->cleanStr($row, 47),
                        'outlet_code_sales_name'   => $this->cleanStr($row, 48),
                        'city_code_outlet_program' => $this->cleanStr($row, 49),
                        'sales_name_outlet_code'   => $this->cleanStr($row, 50),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['imported'] += count($batchData);
                        $batchData = []; // Reset array untuk membebaskan memori
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    if ($stats['skipped_error'] <= 10) {
                        Log::error("Skip row {$index}: ".$e->getMessage());
                    }
                    continue;
                }
            }

            // Sisa batch terakhir
            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['imported'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error("Fatal Import Error: ".$e->getMessage());
            throw $e;
        }
    }

    private function processBatch(array $data)
    {
        // Wrap dalam transaction untuk kecepatan I/O
        DB::transaction(function () use ($data) {
            DB::table('penjualans')->upsert(
                $data,
                ['cabang', 'trans_no', 'kode_item'], // Unique Index
                [
                    'status', 'tgl_penjualan', 'period', 'jatuh_tempo',
                    'kode_pelanggan', 'nama_pelanggan', 'sku', 'no_batch', 'ed', 'nama_item',
                    'qty', 'satuan_jual', 'qty_i', 'satuan_i',
                    'nilai', 'rata2', 'up_percent', 'nilai_up', 'nilai_jual_pembulatan',
                    'd1', 'd2', 'diskon_1', 'diskon_2', 'diskon_bawah', 'total_diskon',
                    'nilai_jual_net', 'total_harga_jual', 'ppn_head', 'total_grand', 'ppn_value',
                    'total_min_ppn', 'margin',
                    'pembayaran', 'cash_bank', 'kode_sales', 'sales_name', 'supplier',
                    'status_pay', 'trx_id',
                    'year', 'month', 'last_suppliers', 'mother_sku', 'divisi', 'program',
                    'outlet_code_sales_name', 'city_code_outlet_program', 'sales_name_outlet_code',
                    'updated_at'
                ]
            );
        });
    }

    // -------------------------------------
    // OPTIMIZED HELPERS
    // -------------------------------------

    private function cleanStr(array &$row, int $index): string
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];
        
        // Paling umum adalah string scalar, handle duluan biar cepat
        if (is_string($v)) {
            // Hapus single quote, trim
            return trim(str_replace("'", "", $v));
        }

        if (is_numeric($v)) {
            return (string)$v;
        }
        
        if ($v === null) return '';

        if ($v instanceof \DateTimeInterface) return $v->format('Y-m-d');

        return is_scalar($v) ? (string)$v : json_encode($v);
    }

    private function parseDateFast(array &$row, int $index): ?string
    {
        if (!isset($row[$index])) return null;
        $v = $row[$index];

        if ($v === '' || $v === null || $v === '-' || $v === 'Blank') return null;

        // Cek jika sudah object DateTime (Excel reader kadang otomatis parse)
        if ($v instanceof \DateTimeInterface) {
            return $v->format('Y-m-d');
        }

        try {
            // Excel serial date (integer seperti 45321)
            if (is_numeric($v)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)->format('Y-m-d');
            }

            // String standar Y-m-d atau d-m-Y
            $ts = strtotime($v);
            return $ts ? date('Y-m-d', $ts) : null;

        } catch (Throwable $e) {
            return null;
        }
    }

    private function numSafeFast(array &$row, int $index)
    {
        if (!isset($row[$index])) return 0;
        $v = $row[$index];

        if ($v === 0 || $v === '0' || $v === 0.0) return 0;
        if ($v === '' || $v === null || $v === '-') return 0;

        if (is_numeric($v)) return $v;

        // Handle format angka Indonesia (1.000,00 -> 1000.00)
        // Hapus semua kecuali angka, titik, koma, minus
        // Ganti koma dengan titik untuk desimal standar PHP
        $clean = str_replace(['.', ','], ['', '.'], (string)$v);
        
        return is_numeric($clean) ? $clean : 0;
    }
}