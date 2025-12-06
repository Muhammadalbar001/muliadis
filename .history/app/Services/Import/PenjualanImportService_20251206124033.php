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
        // 1. Konfigurasi Memori & Waktu
        ini_set('memory_limit', '1024M'); // Pastikan cukup untuk buffer
        ini_set('max_execution_time', 0); // Unlimited time
        DB::disableQueryLog(); // Matikan log query Laravel (Hemat RAM)

        try {
            // 2. Baca File Secara Streaming (Tidak dimuat semua ke RAM)
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'     => 0,
                'imported'       => 0,
                'skipped_empty'  => 0,
                'skipped_error'  => 0,
            ];

            // BATCH SIZE 1000: Sweet spot untuk performa MySQL
            $batchSize = 1000;
            $batchData = [];
            $now       = date('Y-m-d H:i:s');

            // 3. Looping Baris
            foreach ($reader->getRows() as $rawRow) {
                try {
                    $stats['total_rows']++;
                    
                    // Convert ke array index numerik (0, 1, 2...) agar akses cepat
                    $row = array_values($rawRow);

                    // Validasi Cepat: Cek kolom kunci (Trans No & Kode Item)
                    // Menggunakan isset untuk kecepatan, fallback ke string kosong
                    $transNoRaw = isset($row[1]) ? trim((string)$row[1]) : '';
                    $kodeItem   = isset($row[8]) ? trim((string)$row[8]) : '';

                    // Skip jika kosong
                    if ($transNoRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // Skip Header Row (Jika ada baris judul 'Cabang' di tengah data)
                    $cabangRaw = isset($row[0]) ? trim((string)$row[0]) : '';
                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                    // 4. Parsing Tanggal (Pre-calculate biar rapi)
                    $tglPenjualan = $this->parseDateFast($row, 3);
                    $jatuhTempo   = $this->parseDateFast($row, 5);
                    $ed           = $this->parseDateFast($row, 11);

                    // 5. Susun Array Data (Manual mapping lebih cepat dari loop dinamis)
                    $batchData[] = [
                        'cabang'            => $cabangRaw ?: null,
                        'trans_no'          => $transNoRaw,
                        'kode_item'         => $kodeItem,
                        'status'            => $this->getStr($row, 2),
                        
                        'tgl_penjualan'     => $tglPenjualan,
                        'period'            => $this->getStr($row, 4),
                        'jatuh_tempo'       => $jatuhTempo,
                        
                        'kode_pelanggan'    => $this->getStr($row, 6),
                        'nama_pelanggan'    => $this->getStr($row, 7),
                        'sku'               => $this->getStr($row, 9),
                        'no_batch'          => $this->getStr($row, 10),
                        'ed'                => $ed,
                        'nama_item'         => $this->getStr($row, 12),

                        // Angka & Harga
                        'qty'                   => $this->getNum($row, 13),
                        'satuan_jual'           => $this->getStr($row, 14),
                        'qty_i'                 => $this->getNum($row, 15),
                        'satuan_i'              => $this->getStr($row, 16),
                        'nilai'                 => $this->getNum($row, 17),
                        'rata2'                 => $this->getNum($row, 18),
                        'up_percent'            => $this->getNum($row, 19),
                        'nilai_up'              => $this->getNum($row, 20),
                        'nilai_jual_pembulatan' => $this->getNum($row, 21),

                        // Diskon
                        'd1'                => $this->getNum($row, 22),
                        'd2'                => $this->getNum($row, 23),
                        'diskon_1'          => $this->getNum($row, 24),
                        'diskon_2'          => $this->getNum($row, 25),
                        'diskon_bawah'      => $this->getNum($row, 26),
                        'total_diskon'      => $this->getNum($row, 27),

                        // Total
                        'nilai_jual_net'    => $this->getNum($row, 28),
                        'total_harga_jual'  => $this->getNum($row, 29),
                        'ppn_head'          => $this->getNum($row, 30),
                        'total_grand'       => $this->getNum($row, 31),
                        'ppn_value'         => $this->getNum($row, 32),
                        'total_min_ppn'     => $this->getNum($row, 33),
                        'margin'            => $this->getNum($row, 34),

                        // Meta Data
                        'pembayaran'        => $this->getStr($row, 35),
                        'cash_bank'         => $this->getStr($row, 36),
                        'kode_sales'        => $this->getStr($row, 37),
                        'sales_name'        => $this->getStr($row, 38),
                        'supplier'          => $this->getStr($row, 39),
                        'status_pay'        => $this->getStr($row, 40),
                        'trx_id'            => $this->getStr($row, 41),
                        'year'              => $this->getNum($row, 42),
                        'month'             => $this->getNum($row, 43),

                        'last_suppliers'    => $this->getStr($row, 44),
                        'mother_sku'        => $this->getStr($row, 45),
                        'divisi'            => $this->getStr($row, 46),
                        'program'           => $this->getStr($row, 47),
                        'outlet_code_sales_name'   => $this->getStr($row, 48),
                        'city_code_outlet_program' => $this->getStr($row, 49),
                        'sales_name_outlet_code'   => $this->getStr($row, 50),

                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    // 6. Eksekusi Batch jika sudah 1000 baris
                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['imported'] += count($batchData);
                        $batchData = []; // Kosongkan memori
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    // Log error sampel saja (jangan semua agar log tidak penuh)
                    if ($stats['skipped_error'] <= 5) {
                        Log::error("Import Row Error: " . $e->getMessage());
                    }
                }
            }

            // 7. Proses Sisa Data Terakhir
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

    /**
     * Proses Insert/Update ke Database dengan Transaction
     */
    private function processBatch(array $data)
    {
        if (empty($data)) return;

        // Gunakan Transaction untuk kecepatan I/O
        DB::transaction(function () use ($data) {
            // Upsert: Update jika ada, Insert jika baru
            // Kunci Unik: cabang, trans_no, kode_item (Pastikan index ini ada di migration!)
            DB::table('penjualans')->upsert(
                $data,
                ['cabang', 'trans_no', 'kode_item'], 
                [
                    // Daftar kolom yang boleh di-update (selain primary key & created_at)
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
    // FAST HELPERS (Tanpa Regex Berat)
    // -------------------------------------

    // Ambil string bersih
    private function getStr(array &$row, int $index): string
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];
        if (is_string($v)) return trim($v);
        if (is_numeric($v)) return (string)$v;
        return '';
    }

    // Ambil angka bersih
    private function getNum(array &$row, int $index)
    {
        if (!isset($row[$index])) return 0;
        $v = $row[$index];
        
        if (is_numeric($v)) return $v;
        if ($v === '' || $v === null || $v === '-') return 0;

        // Bersihkan format (misal: "1.000,00" -> "1000.00")
        // Hapus titik ribuan, ganti koma desimal jadi titik
        $clean = str_replace(['.', ','], ['', '.'], (string)$v);
        
        return is_numeric($clean) ? $clean : 0;
    }

    // Parsing Tanggal Cepat
    private function parseDateFast(array &$row, int $index): ?string
    {
        if (!isset($row[$index])) return null;
        $v = $row[$index];

        if (!$v || $v === '-' || $v === 'Blank') return null;

        try {
            // Excel Serial Date (Contoh: 45321)
            if (is_numeric($v)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)->format('Y-m-d');
            }
            // String Date (Y-m-d atau d-m-Y)
            $ts = strtotime($v);
            return $ts ? date('Y-m-d', $ts) : null;
        } catch (Throwable $e) {
            return null;
        }
    }
}