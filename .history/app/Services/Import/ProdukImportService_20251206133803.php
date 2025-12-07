<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProdukImportService
{
    public function handle(string $filePath)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);
        DB::disableQueryLog();

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'     => 0,
                'processed'      => 0,
                'skipped_empty'  => 0,
                'duplicates_marked' => 0, // Menghitung yang ditandai duplikat
            ];

            $batchSize = 1000;
            $batchData = [];
            $now       = date('Y-m-d H:i:s');
            
            $lastCabang = null; 
            
            // Array untuk melacak duplikat DI DALAM FILE INI
            // Format Key: "namacabang|sku"
            $seenKeys = []; 

            foreach ($reader->getRows() as $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values($rawRow);

                    // --- 1. FILL DOWN CABANG ---
                    $currentCabang = $this->getStr($row, 0);
                    if ($currentCabang !== '') {
                        $lastCabang = $currentCabang;
                    } else {
                        $currentCabang = $lastCabang;
                    }
                    
                    if (empty($currentCabang) || strcasecmp($currentCabang, 'cabang') === 0) continue;

                    // --- 2. SKU CHECK ---
                    $skuRaw   = $this->getStr($row, 2);
                    $ccodeRaw = $this->getStr($row, 1);
                    $finalSku = $skuRaw !== '' ? $skuRaw : $ccodeRaw;

                    if ($finalSku === '') {
                        $stats['skipped_empty']++;
                        continue; 
                    }

                    // --- 3. CEK DUPLIKAT (LOGIC BARU) ---
                    $uniqueKey = strtolower(trim($currentCabang) . '|' . trim($finalSku));
                    $isDuplicate = false;

                    if (isset($seenKeys[$uniqueKey])) {
                        // Sudah pernah muncul di baris sebelumnya -> TANDAI DUPLIKAT
                        $isDuplicate = true;
                        $stats['duplicates_marked']++;
                    } else {
                        // Belum pernah muncul, catat
                        $seenKeys[$uniqueKey] = true;
                    }

                    // --- 4. MAPPING DATA ---
                    $batchData[] = [
                        // Penanda Duplikat
                        'is_duplicate'      => $isDuplicate ? 1 : 0,

                        'cabang'            => $currentCabang,
                        'ccode'             => $ccodeRaw,
                        'sku'               => $finalSku,
                        'kategori'          => $this->getStr($row, 3),
                        'name_item'         => $this->getStr($row, 4),
                        'expired_date'      => $this->getDate($row, 5),

                        'stok'              => $this->getStr($row, 6),
                        'oum'               => $this->getStr($row, 7),

                        'good'              => $this->getStr($row, 8),
                        'good_konversi'     => $this->getStr($row, 9),
                        'ktn'               => $this->getStr($row, 10),
                        'good_amount'       => $this->getStr($row, 11),

                        'avg_3m_in_oum'     => $this->getStr($row, 12),
                        'avg_3m_in_ktn'     => $this->getStr($row, 13),
                        'avg_3m_in_value'   => $this->getStr($row, 14),
                        'not_move_3m'       => $this->getStr($row, 15),

                        'bad'               => $this->getStr($row, 16),
                        'bad_konversi'      => $this->getStr($row, 17),
                        'bad_ktn'           => $this->getStr($row, 18),
                        'bad_amount'        => $this->getStr($row, 19),

                        'wrh1'              => $this->getStr($row, 20),
                        'wrh1_konversi'     => $this->getStr($row, 21),
                        'wrh1_amount'       => $this->getStr($row, 22),
                        'wrh2'              => $this->getStr($row, 23),
                        'wrh2_konversi'     => $this->getStr($row, 24),
                        'wrh2_amount'       => $this->getStr($row, 25),
                        'wrh3'              => $this->getStr($row, 26),
                        'wrh3_konversi'     => $this->getStr($row, 27),
                        'wrh3_amount'       => $this->getStr($row, 28),

                        'good_storage'      => $this->getStr($row, 29),
                        'sell_per_week'     => $this->getStr($row, 30),
                        'blank_field'       => $this->getStr($row, 31),
                        'empty_field'       => $this->getStr($row, 32),
                        'min'               => $this->getStr($row, 33),
                        're_qty'            => $this->getStr($row, 34),
                        'expired_info'      => $this->getDate($row, 35),

                        'buy'               => $this->getStr($row, 36),
                        'buy_disc'          => $this->getStr($row, 37),
                        'buy_in_ktn'        => $this->getStr($row, 38),
                        'avg'               => $this->getStr($row, 39),
                        'total'             => $this->getStr($row, 40),

                        'up'                => $this->getStr($row, 41),
                        'fix'               => $this->getStr($row, 42),
                        'ppn'               => $this->getStr($row, 43),
                        'fix_exc_ppn'       => $this->getStr($row, 44),
                        'margin'            => $this->getStr($row, 45),
                        'percent_margin'    => $this->getStr($row, 46),
                        'order_no'          => $this->getStr($row, 47),

                        'supplier'          => $this->getStr($row, 48),
                        'mother_sku'        => $this->getStr($row, 49),
                        'last_supplier'     => $this->getStr($row, 50),
                        'divisi'            => $this->getStr($row, 51),
                        'unique_id'         => $this->getStr($row, 52),

                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];

                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['processed'] += count($batchData);
                        $batchData = [];
                    }

                } catch (Throwable $e) {
                    // Error handler silent
                }
            }

            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            throw $e;
        }
    }

    private function processBatch(array $data)
    {
        if (empty($data)) return;

        // GANTI DARI UPSERT KE INSERT (Karena kita mau duplicate masuk)
        DB::table('produks')->insert($data);
    }

    // Helpers
    private function getStr(array &$row, int $index): string
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];
        if (is_null($v)) return '';
        $str = trim((string)$v);
        if (str_starts_with($str, "'")) $str = substr($str, 1);
        return $str;
    }

    private function getDate(array &$row, int $index): ?string
    {
        $v = $this->getStr($row, $index);
        if ($v === '' || $v === '-' || $v === 'Blank') return null;
        try {
            if (is_numeric($v)) return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)->format('Y-m-d');
            $ts = strtotime($v);
            return $ts ? date('Y-m-d', $ts) : null;
        } catch (Throwable $e) { return null; }
    }
}