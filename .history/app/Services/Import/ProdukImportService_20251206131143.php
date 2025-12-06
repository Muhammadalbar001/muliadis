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
                'skipped_error'  => 0,
            ];

            $batchSize = 1000;
            $batchData = [];
            $now       = date('Y-m-d H:i:s');
            
            // Variabel untuk fitur "Fill Down" (Isi otomatis ke bawah jika Cabang kosong/merged)
            $lastCabang = null; 

            foreach ($reader->getRows() as $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values($rawRow);

                    // --- 1. FILL DOWN CABANG ---
                    $currentCabang = $this->getStr($row, 0); // Kolom A

                    if ($currentCabang !== '') {
                        $lastCabang = $currentCabang; // Update referensi cabang terakhir
                    } else {
                        $currentCabang = $lastCabang; // Pakai cabang dari baris sebelumnya
                    }

                    // Jika masih kosong (baris paling atas kosong), skip
                    if (empty($currentCabang) || strcasecmp($currentCabang, 'cabang') === 0) {
                        continue;
                    }

                    // --- 2. SOLUSI SKU KOSONG (KUNCI PERBAIKAN) ---
                    $skuRaw   = $this->getStr($row, 2); // Kolom C
                    $ccodeRaw = $this->getStr($row, 1); // Kolom B
                    
                    // Jika SKU kosong, PAKAI CCODE. Jika CCODE kosong juga, lewati.
                    $finalSku = $skuRaw !== '' ? $skuRaw : $ccodeRaw;

                    if ($finalSku === '') {
                        $stats['skipped_empty']++;
                        continue; 
                    }

                    // --- 3. MAPPING DATA (53 KOLOM) ---
                    $batchData[] = [
                        'cabang'            => $currentCabang,
                        'ccode'             => $ccodeRaw,
                        'sku'               => $finalSku, // Gunakan SKU hasil perbaikan
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

                    // Eksekusi Batch
                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['processed'] += count($batchData);
                        $batchData = [];
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    if ($stats['skipped_error'] <= 5) {
                        Log::error("Import Row Error: " . $e->getMessage());
                    }
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

        DB::transaction(function () use ($data) {
            // UPSERT: Update jika (cabang + sku) sama, Insert jika beda
            DB::table('produks')->upsert(
                $data,
                ['cabang', 'sku'], // Kunci Unik (Pastikan Anda sudah menjalankan migration unique index)
                [
                    'ccode', 'kategori', 'name_item', 'expired_date',
                    'stok', 'oum', 
                    'good', 'good_konversi', 'ktn', 'good_amount',
                    'avg_3m_in_oum', 'avg_3m_in_ktn', 'avg_3m_in_value', 'not_move_3m',
                    'bad', 'bad_konversi', 'bad_ktn', 'bad_amount',
                    'wrh1', 'wrh1_konversi', 'wrh1_amount',
                    'wrh2', 'wrh2_konversi', 'wrh2_amount',
                    'wrh3', 'wrh3_konversi', 'wrh3_amount',
                    'good_storage', 'sell_per_week', 'blank_field', 'empty_field',
                    'min', 're_qty', 'expired_info',
                    'buy', 'buy_disc', 'buy_in_ktn', 'avg', 'total',
                    'up', 'fix', 'ppn', 'fix_exc_ppn', 'margin', 'percent_margin', 'order_no',
                    'supplier', 'mother_sku', 'last_supplier', 'divisi', 'unique_id',
                    'updated_at'
                ]
            );
        });
    }

    // Helper: Bersihkan string & tanda petik excel
    private function getStr(array &$row, int $index): string
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];
        if (is_null($v)) return '';
        
        $str = trim((string)$v);
        // Hapus tanda petik tunggal di awal
        if (str_starts_with($str, "'")) {
            $str = substr($str, 1);
        }
        return $str;
    }

    // Helper: Parse Date
    private function getDate(array &$row, int $index): ?string
    {
        $v = $this->getStr($row, $index);
        if ($v === '' || $v === '-' || $v === 'Blank') return null;

        try {
            if (is_numeric($v)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)->format('Y-m-d');
            }
            $ts = strtotime($v);
            return $ts ? date('Y-m-d', $ts) : null;
        } catch (Throwable $e) {
            return null;
        }
    }
}