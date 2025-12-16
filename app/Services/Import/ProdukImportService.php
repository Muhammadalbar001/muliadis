<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class ProdukImportService
{
    public function handle(string $filePath)
    {
        // 1. Konfigurasi Resource (Hemat & Cepat)
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
        DB::disableQueryLog();

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'       => 0,
                'processed'        => 0,
                'skipped_empty'    => 0,
                'duplicates_found' => 0,
            ];

            $batchSize = 500;
            $batchData = [];
            $now       = date('Y-m-d H:i:s');
            
            // Variable Fill Down
            $lastCabang = null;
            
            // Pelacak Duplikat (In-Memory)
            // Key: "cabang|sku"
            $seenKeys = [];

            foreach ($reader->getRows() as $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values($rawRow); // Index numerik agar cepat

                    // Ambil Data Utama
                    $cabangRaw = isset($row[0]) ? trim((string)$row[0]) : '';
                    $skuRaw    = isset($row[2]) ? trim((string)$row[2]) : '';
                    $ccodeRaw  = isset($row[1]) ? trim((string)$row[1]) : '';

                    // Skip Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                    // --- LOGIKA FILL DOWN CABANG ---
                    if ($cabangRaw !== '') {
                        $lastCabang = $cabangRaw;
                    }
                    $finalCabang = $cabangRaw ?: $lastCabang;

                    // Validasi: SKU/CCODE harus ada
                    $finalSku = $skuRaw !== '' ? $skuRaw : $ccodeRaw;
                    
                    if ($finalSku === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }
                    
                    // --- CEK DUPLIKAT (Dalam File) ---
                    $uniqueKey = strtolower($finalCabang . '|' . $finalSku);
                    $isDuplicate = false;

                    if (isset($seenKeys[$uniqueKey])) {
                        $isDuplicate = true;
                        $stats['duplicates_found']++;
                    } else {
                        $seenKeys[$uniqueKey] = true;
                    }

                    // --- MAPPING DATA (53 Kolom) ---
                    $batchData[] = [
                        'is_duplicate'      => $isDuplicate ? 1 : 0,

                        'cabang'            => $finalCabang,
                        'ccode'             => $ccodeRaw,
                        'sku'               => $finalSku,
                        'kategori'          => $this->getStr($row, 3),
                        'name_item'         => $this->getStr($row, 4),
                        'expired_date'      => $this->fastDateParse($this->getStr($row, 5)),

                        'stok'              => $this->fastNum($this->getStr($row, 6)),
                        'oum'               => $this->getStr($row, 7),

                        'good'              => $this->fastNum($this->getStr($row, 8)),
                        'good_konversi'     => $this->getStr($row, 9),
                        'ktn'               => $this->fastNum($this->getStr($row, 10)),
                        'good_amount'       => $this->fastNum($this->getStr($row, 11)),

                        'avg_3m_in_oum'     => $this->fastNum($this->getStr($row, 12)),
                        'avg_3m_in_ktn'     => $this->fastNum($this->getStr($row, 13)),
                        'avg_3m_in_value'   => $this->fastNum($this->getStr($row, 14)),
                        'not_move_3m'       => $this->getStr($row, 15),

                        'bad'               => $this->fastNum($this->getStr($row, 16)),
                        'bad_konversi'      => $this->getStr($row, 17),
                        'bad_ktn'           => $this->fastNum($this->getStr($row, 18)),
                        'bad_amount'        => $this->fastNum($this->getStr($row, 19)),

                        'wrh1'              => $this->fastNum($this->getStr($row, 20)),
                        'wrh1_konversi'     => $this->getStr($row, 21),
                        'wrh1_amount'       => $this->fastNum($this->getStr($row, 22)),
                        'wrh2'              => $this->fastNum($this->getStr($row, 23)),
                        'wrh2_konversi'     => $this->getStr($row, 24),
                        'wrh2_amount'       => $this->fastNum($this->getStr($row, 25)),
                        'wrh3'              => $this->fastNum($this->getStr($row, 26)),
                        'wrh3_konversi'     => $this->getStr($row, 27),
                        'wrh3_amount'       => $this->fastNum($this->getStr($row, 28)),

                        'good_storage'      => $this->getStr($row, 29),
                        'sell_per_week'     => $this->fastNum($this->getStr($row, 30)),
                        'blank_field'       => $this->getStr($row, 31),
                        'empty_field'       => $this->getStr($row, 32),
                        'min'               => $this->fastNum($this->getStr($row, 33)),
                        're_qty'            => $this->fastNum($this->getStr($row, 34)),
                        'expired_info'      => $this->fastDateParse($this->getStr($row, 35)),

                        'buy'               => $this->fastNum($this->getStr($row, 36)),
                        'buy_disc'          => $this->fastNum($this->getStr($row, 37)),
                        'buy_in_ktn'        => $this->fastNum($this->getStr($row, 38)),
                        'avg'               => $this->fastNum($this->getStr($row, 39)),
                        'total'             => $this->fastNum($this->getStr($row, 40)),

                        'up'                => $this->fastNum($this->getStr($row, 41)),
                        'fix'               => $this->fastNum($this->getStr($row, 42)),
                        'ppn'               => $this->fastNum($this->getStr($row, 43)),
                        'fix_exc_ppn'       => $this->fastNum($this->getStr($row, 44)),
                        'margin'            => $this->fastNum($this->getStr($row, 45)),
                        'percent_margin'    => $this->fastNum($this->getStr($row, 46)),
                        'order_no'          => $this->getStr($row, 47),

                        'supplier'          => $this->getStr($row, 48),
                        'mother_sku'        => $this->getStr($row, 49),
                        'last_supplier'     => $this->getStr($row, 50),
                        'divisi'            => $this->getStr($row, 51),
                        'unique_id'         => $this->getStr($row, 52),

                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];

                    // Insert Batch
                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['processed'] += count($batchData);
                        $batchData = [];
                    }

                } catch (Throwable $e) {
                    // Skip row error
                }
            }

            // Insert Sisa
            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error("Import Produk Gagal: " . $e->getMessage());
            throw $e;
        }
    }

    private function processBatch(array $data)
    {
        if (empty($data)) return;
        // Gunakan INSERT (Bukan Upsert/Ignore) karena tabel produk sudah bersih dari unique index
        DB::table('produks')->insert($data);
    }

    // --- HELPER SUPER CEPAT ---
    private function getStr(array &$row, int $index): string
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];
        if (is_null($v)) return '';
        $str = trim((string)$v);
        if (str_starts_with($str, "'")) $str = substr($str, 1);
        return $str;
    }

    private function fastNum($val)
    {
        if (is_numeric($val)) return $val;
        if (!$val || $val === '-' || $val === '') return 0;
        return str_replace([',', ' '], '', $val);
    }

    private function fastDateParse($val)
    {
        if (!$val || $val === '-' || $val === 'Blank') return null;
        if (str_starts_with($val, "'")) $val = substr($val, 1);

        if (is_numeric($val)) {
            $unixDate = ($val - 25569) * 86400;
            return gmdate("Y-m-d", $unixDate);
        }
        $ts = strtotime($val);
        return $ts ? date('Y-m-d', $ts) : null;
    }
}