<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;

class SalesImportService
{
    public function handle(string $filePath)
    {
        // 1. Setup Resource
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
        DB::disableQueryLog();

        try {
            // Baca file tanpa header
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows' => 0,
                'processed'  => 0,
                'skipped_empty' => 0,
            ];

            $batchSize = 500;
            $batchData = [];
            $now       = now();

            foreach ($reader->getRows() as $rawRow) {
                $stats['total_rows']++;
                $row = array_values($rawRow);

                /**
                 * SESUAIKAN INDEX BERDASARKAN FILE EXCEL ANDA:
                 * Jika Excel Anda: Kode Sales | Nama Sales | Divisi | Status | City
                 * Maka indexnya: 0: Kode, 1: Nama, 2: Divisi, 3: Status, 4: City
                 */

                $salesCode = isset($row[0]) ? trim((string)$row[0]) : null;
                $salesName = isset($row[1]) ? trim((string)$row[1]) : '';

                // Skip jika Nama Sales kosong atau baris Header
                if ($salesName === '' || strcasecmp($salesName, 'Sales') === 0 || strcasecmp($salesName, 'Nama Sales') === 0) {
                    $stats['skipped_empty']++;
                    continue;
                }

                $batchData[] = [
                    'sales_code' => $salesCode, // Kolom baru
                    'sales_name' => $salesName,
                    'divisi'     => isset($row[2]) ? trim((string)$row[2]) : '',
                    'status'     => isset($row[3]) ? trim((string)$row[3]) : 'Active',
                    'city'       => isset($row[4]) ? trim((string)$row[4]) : '',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Insert per 500 baris
                if (count($batchData) >= $batchSize) {
                    $this->processBatch($batchData);
                    $stats['processed'] += count($batchData);
                    $batchData = [];
                }
            }

            // Insert sisa data
            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error("Import Sales Error: " . $e->getMessage());
            // Berikan info error yang lebih spesifik jika terjadi crash
            throw new \Exception("Gagal Import: " . $e->getMessage());
        }
    }

    private function processBatch(array $data)
    {
        if (empty($data)) return;
        
        // Menggunakan insertOrIgnore agar jika ada Kode Sales yang duplikat tidak menyebabkan Error 500
        DB::table('sales')->insertOrIgnore($data);
    }
}