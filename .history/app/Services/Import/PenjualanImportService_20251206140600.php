<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class PenjualanImportService
{
    public function handle(string $filePath)
    {
        ini_set('memory_limit', '2048M'); // RAM besar untuk file transaksi
        ini_set('max_execution_time', 0);
        DB::disableQueryLog();

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'     => 0,
                'processed'      => 0,
                'skipped_empty'  => 0,
                'skipped_error'  => 0,
            ];

            $batchSize = 1000;
            $batchData = [];
            $now       = date('Y-m-d H:i:s');

            // --- VARIABLE FILL DOWN (Mengingat data baris sebelumnya) ---
            $lastCabang = null;
            $lastTransNo = null;
            $lastStatus = null;
            $lastTgl = null;
            $lastPeriod = null;
            $lastJatuhTempo = null;
            $lastKodePel = null;
            $lastNamaPel = null;
            $lastSales = null; // Kadang sales juga di-merge

            foreach ($reader->getRows() as $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values($rawRow);

                    // 1. AMBIL DATA KUNCI (RAW)
                    $cabangRaw   = $this->getStr($row, 0);
                    $transNoRaw  = $this->getStr($row, 1);
                    $kodeItem    = $this->getStr($row, 8); // Kunci Item

                    // Skip Baris Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                    // 2. LOGIKA FILL DOWN (PENTING!)
                    // Jika Trans No kosong, berarti ini adalah baris ITEM dari invoice di atasnya.
                    // Kita pakai data dari $last...
                    
                    if ($transNoRaw !== '') {
                        // Ini Baris Header Invoice Baru -> Update Reference
                        $lastCabang     = $cabangRaw ?: $lastCabang; // Kadang cabang juga kosong
                        $lastTransNo    = $transNoRaw;
                        $lastStatus     = $this->getStr($row, 2);
                        $lastTgl        = $this->getDate($row, 3);
                        $lastPeriod     = $this->getStr($row, 4);
                        $lastJatuhTempo = $this->getDate($row, 5);
                        $lastKodePel    = $this->getStr($row, 6);
                        $lastNamaPel    = $this->getStr($row, 7);
                        // $lastSales   = $this->getStr($row, 38); // Sales biasanya ada di setiap baris atau di header
                    } 
                    
                    // Pastikan kita punya Trans No (Entah dari baris ini atau sebelumnya)
                    $finalTransNo = $transNoRaw ?: $lastTransNo;

                    // Validasi: Jika tidak ada Trans No sama sekali, skip
                    if (empty($finalTransNo)) {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // Jika Kode Item kosong, tapi ada Trans No, mungkin ini baris total/footer di Excel -> Skip
                    if ($kodeItem === '') {
                        continue; 
                    }

                    // 3. MAPPING 51 KOLOM
                    $batchData[] = [
                        // Identitas & Header (Pakai Variable Last/Final)
                        'cabang'            => $cabangRaw ?: $lastCabang, 
                        'trans_no'          => $finalTransNo,
                        'status'            => $this->getStr($row, 2) ?: $lastStatus,
                        'tgl_penjualan'     => $this->getDate($row, 3) ?: $lastTgl,
                        'period'            => $this->getStr($row, 4) ?: $lastPeriod,
                        'jatuh_tempo'       => $this->getDate($row, 5) ?: $lastJatuhTempo,
                        'kode_pelanggan'    => $this->getStr($row, 6) ?: $lastKodePel,
                        'nama_pelanggan'    => $this->getStr($row, 7) ?: $lastNamaPel,

                        // Item Detail
                        'kode_item'         => $kodeItem,
                        'sku'               => $this->getStr($row, 9),
                        'no_batch'          => $this->getStr($row, 10),
                        'ed'                => $this->getDate($row, 11),
                        'nama_item'         => $this->getStr($row, 12),

                        // Kuantitas & Harga
                        'qty'               => $this->getStr($row, 13), // String agar aman
                        'satuan_jual'       => $this->getStr($row, 14),
                        'qty_i'             => $this->getStr($row, 15),
                        'satuan_i'          => $this->getStr($row, 16),
                        'nilai'             => $this->getStr($row, 17),
                        'rata2'             => $this->getStr($row, 18),
                        'up_percent'        => $this->getStr($row, 19),
                        'nilai_up'          => $this->getStr($row, 20),
                        'nilai_jual_pembulatan' => $this->getStr($row, 21),

                        // Diskon
                        'd1'                => $this->getStr($row, 22),
                        'd2'                => $this->getStr($row, 23),
                        'diskon_1'          => $this->getStr($row, 24),
                        'diskon_2'          => $this->getStr($row, 25),
                        'diskon_bawah'      => $this->getStr($row, 26),
                        'total_diskon'      => $this->getStr($row, 27),

                        // Total
                        'nilai_jual_net'    => $this->getStr($row, 28),
                        'total_harga_jual'  => $this->getStr($row, 29),
                        'ppn_head'          => $this->getStr($row, 30),
                        'total_grand'       => $this->getStr($row, 31),
                        'ppn_value'         => $this->getStr($row, 32),
                        'total_min_ppn'     => $this->getStr($row, 33),
                        'margin'            => $this->getStr($row, 34),

                        // Meta Pembayaran & Sales
                        'pembayaran'        => $this->getStr($row, 35),
                        'cash_bank'         => $this->getStr($row, 36),
                        'kode_sales'        => $this->getStr($row, 37),
                        'sales_name'        => $this->getStr($row, 38),
                        'supplier'          => $this->getStr($row, 39),
                        'status_pay'        => $this->getStr($row, 40),
                        'trx_id'            => $this->getStr($row, 41), // ID Unik dari Excel
                        'year'              => $this->getStr($row, 42),
                        'month'             => $this->getStr($row, 43),
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

                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['processed'] += count($batchData);
                        $batchData = [];
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                }
            }

            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error("Import Penjualan Error: " . $e->getMessage());
            throw $e;
        }
    }

    private function processBatch(array $data)
    {
        if (empty($data)) return;

        DB::transaction(function () use ($data) {
            // Upsert berdasarkan (Cabang, Trans No, Kode Item) agar tidak duplikat
            // Jika TRX ID (Kolom 41) unik per baris item, tambahkan ke unique key
            DB::table('penjualans')->upsert(
                $data,
                ['cabang', 'trans_no', 'kode_item'], 
                [
                    'status', 'tgl_penjualan', 'period', 'jatuh_tempo', 'kode_pelanggan', 'nama_pelanggan',
                    'sku', 'no_batch', 'ed', 'nama_item',
                    'qty', 'satuan_jual', 'qty_i', 'satuan_i',
                    'nilai', 'rata2', 'up_percent', 'nilai_up', 'nilai_jual_pembulatan',
                    'd1', 'd2', 'diskon_1', 'diskon_2', 'diskon_bawah', 'total_diskon',
                    'nilai_jual_net', 'total_harga_jual', 'ppn_head', 'total_grand', 'ppn_value',
                    'total_min_ppn', 'margin',
                    'pembayaran', 'cash_bank', 'kode_sales', 'sales_name', 'supplier', 'status_pay',
                    'trx_id', 'year', 'month', 'last_suppliers', 'mother_sku', 'divisi', 'program',
                    'outlet_code_sales_name', 'city_code_outlet_program', 'sales_name_outlet_code',
                    'updated_at'
                ]
            );
        });
    }

    // --- HELPER ---
    private function getStr(array &$row, int $index): string
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];
        if (is_null($v)) return '';
        $str = trim((string)$v);
        if (str_starts_with($str, "'")) $str = substr($str, 1); // Hapus petik
        return $str;
    }

    private function getDate(array &$row, int $index): ?string
    {
        $v = $this->getStr($row, $index);
        if (!$v || $v === '-' || $v === 'Blank') return null;
        try {
            if (is_numeric($v)) return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)->format('Y-m-d');
            $ts = strtotime($v);
            return $ts ? date('Y-m-d', $ts) : null;
        } catch (Throwable $e) { return null; }
    }
}