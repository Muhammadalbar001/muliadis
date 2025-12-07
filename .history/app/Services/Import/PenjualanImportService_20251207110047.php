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
        // 1. Setup Resource yang Besar
        ini_set('memory_limit', '2048M'); // 2GB RAM untuk file besar
        ini_set('max_execution_time', 0); // Unlimited time
        DB::disableQueryLog();

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'     => 0,
                'processed'      => 0,
                'skipped_empty'  => 0, // Harusnya 0 jika semua barang punya kode
            ];

            $batchSize = 2000; // Batch besar agar cepat
            $batchData = [];
            $now       = date('Y-m-d H:i:s');

            // --- VARIABLES UNTUK FILL DOWN (Mengingat data baris atas) ---
            $lastCabang     = null;
            $lastTransNo    = null;
            $lastStatus     = null;
            $lastTgl        = null;
            $lastPeriod     = null;
            $lastJatuhTempo = null;
            $lastKodePel    = null;
            $lastNamaPel    = null;

            foreach ($reader->getRows() as $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values($rawRow);

                    // Ambil Data Utama
                    $cabangRaw   = $this->getStr($row, 0); // Kolom A
                    $transNoRaw  = $this->getStr($row, 1); // Kolom B
                    $kodeItem    = $this->getStr($row, 8); // Kolom I (Item)

                    // Skip Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                    // --- LOGIKA FILL DOWN PENTING ---
                    // Jika ada Trans No, berarti ini HEADER INVOICE BARU.
                    // Kita simpan datanya ke variabel $last... untuk dipakai baris bawahnya.
                    if ($transNoRaw !== '') {
                        $lastTransNo    = $transNoRaw;
                        $lastCabang     = $cabangRaw ?: $lastCabang; // Kadang cabang juga kosong di merge
                        $lastStatus     = $this->getStr($row, 2);
                        
                        // Parse Tanggal dengan pembersih tanda petik
                        $lastTgl        = $this->parseDateRobust($this->getStr($row, 3));
                        $lastPeriod     = $this->getStr($row, 4);
                        $lastJatuhTempo = $this->parseDateRobust($this->getStr($row, 5));
                        
                        $lastKodePel    = $this->getStr($row, 6);
                        $lastNamaPel    = $this->getStr($row, 7);
                    }

                    // Tentukan Data Final untuk baris ini
                    // Gunakan data baris ini, kalau kosong pakai data terakhir ($last...)
                    $finalTransNo = $transNoRaw ?: $lastTransNo;
                    
                    // Validasi: Kalau Trans No masih kosong juga (artinya dari baris pertama Excel kosong), skip
                    if (empty($finalTransNo)) {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // Validasi: Kalau Kode Item kosong, skip (Mungkin baris total/footer/kosong)
                    if (empty($kodeItem)) {
                        continue;
                    }

                    // --- MAPPING DATA (51 KOLOM) ---
                    $batchData[] = [
                        // Identitas (Fill Down)
                        'cabang'            => $cabangRaw ?: $lastCabang,
                        'trans_no'          => $finalTransNo,
                        'status'            => $this->getStr($row, 2) ?: $lastStatus,
                        'tgl_penjualan'     => $lastTgl, // Pastikan tidak null!
                        'period'            => $this->getStr($row, 4) ?: $lastPeriod,
                        'jatuh_tempo'       => $lastJatuhTempo,
                        'kode_pelanggan'    => $this->getStr($row, 6) ?: $lastKodePel,
                        'nama_pelanggan'    => $this->getStr($row, 7) ?: $lastNamaPel,

                        // Item
                        'kode_item'         => $kodeItem,
                        'sku'               => $this->getStr($row, 9),
                        'no_batch'          => $this->getStr($row, 10),
                        'ed'                => $this->parseDateRobust($this->getStr($row, 11)),
                        'nama_item'         => $this->getStr($row, 12),

                        // Angka-angka
                        'qty'               => $this->getStr($row, 13),
                        'satuan_jual'       => $this->getStr($row, 14),
                        'qty_i'             => $this->getStr($row, 15),
                        'satuan_i'          => $this->getStr($row, 16),
                        'nilai'             => $this->getStr($row, 17),
                        'rata2'             => $this->getStr($row, 18),
                        'up_percent'        => $this->getStr($row, 19),
                        'nilai_up'          => $this->getStr($row, 20),
                        'nilai_jual_pembulatan' => $this->getStr($row, 21),

                        'd1'                => $this->getStr($row, 22),
                        'd2'                => $this->getStr($row, 23),
                        'diskon_1'          => $this->getStr($row, 24),
                        'diskon_2'          => $this->getStr($row, 25),
                        'diskon_bawah'      => $this->getStr($row, 26),
                        'total_diskon'      => $this->getStr($row, 27),

                        'nilai_jual_net'    => $this->getStr($row, 28),
                        'total_harga_jual'  => $this->getStr($row, 29),
                        'ppn_head'          => $this->getStr($row, 30),
                        'total_grand'       => $this->getStr($row, 31),
                        'ppn_value'         => $this->getStr($row, 32),
                        'total_min_ppn'     => $this->getStr($row, 33),
                        'margin'            => $this->getStr($row, 34),

                        // Meta
                        'pembayaran'        => $this->getStr($row, 35),
                        'cash_bank'         => $this->getStr($row, 36),
                        'kode_sales'        => $this->getStr($row, 37),
                        'sales_name'        => $this->getStr($row, 38),
                        'supplier'          => $this->getStr($row, 39),
                        'status_pay'        => $this->getStr($row, 40),
                        'trx_id'            => $this->getStr($row, 41),
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

                    // Eksekusi Batch
                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['processed'] += count($batchData);
                        $batchData = [];
                    }

                } catch (Throwable $e) {
                    // Skip row error diam-diam agar tidak memutus proses
                }
            }

            // Sisa Batch Terakhir
            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error("Fatal Import Error: ".$e->getMessage());
            throw $e;
        }
    }

    private function processBatch(array $data)
    {
        if (empty($data)) return;

        // Gunakan INSERT (Bukan Upsert) agar cepat & semua baris masuk
        // Asumsi: Tabel Penjualan sudah tidak punya Unique Index yang ketat
        DB::table('penjualans')->insertOrIgnore($data);
    }

    // --- HELPER AGRESIF (PEMBERSIH KOTORAN EXCEL) ---

    private function getStr(array &$row, int $index): string
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];
        if (is_null($v)) return '';
        
        $str = trim((string)$v);
        // Hapus tanda petik tunggal di awal (Masalah utama tanggal Anda)
        if (str_starts_with($str, "'")) {
            $str = substr($str, 1);
        }
        return $str;
    }

    private function parseDateRobust($value): ?string
    {
        if (!$value || $value === '-' || $value === 'Blank') return null;
        
        // Bersihkan lagi untuk memastikan
        $clean = trim((string)$value);
        if (str_starts_with($clean, "'")) $clean = substr($clean, 1);

        try {
            // Coba format Excel Numeric (45321)
            if (is_numeric($clean)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($clean)->format('Y-m-d');
            }
            // Coba format String (2025-01-01)
            return Carbon::parse($clean)->format('Y-m-d');
        } catch (Throwable $e) {
            return null;
        }
    }
}