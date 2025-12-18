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
        ini_set('memory_limit', '512M');
        DB::disableQueryLog();

        try {
            // Membaca file tanpa header row sesuai instruksi sebelumnya
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = ['total_rows' => 0, 'processed' => 0, 'skipped_empty' => 0];
            $batchData = [];
            $now = now();

            foreach ($reader->getRows() as $rawRow) {
                $stats['total_rows']++;
                $row = array_values($rawRow);

                // MAPPING BERDASARKAN sales.xlsx
                // 0: Sales (Nama)
                // 1: Divisi
                // 2: Status
                // 3: Target IMS (Akan diabaikan di tabel sales, masuk ke sales_targets jika perlu)
                // 4: Target OA (Akan diabaikan)
                // 5: City
                
                $salesName = isset($row[0]) ? trim((string)$row[0]) : '';

                // Skip header atau baris kosong
                if ($salesName === '' || strcasecmp($salesName, 'Sales') === 0) {
                    $stats['skipped_empty']++;
                    continue;
                }

                $batchData[] = [
                    'sales_code' => null, // Kode Sales dibiarkan kosong untuk di-sync nanti
                    'sales_name' => $salesName,
                    'divisi'     => isset($row[1]) ? trim((string)$row[1]) : '',
                    'status'     => isset($row[2]) ? trim((string)$row[2]) : 'Active',
                    'city'       => isset($row[5]) ? trim((string)$row[5]) : '',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($batchData) >= 500) {
                    DB::table('sales')->insertOrIgnore($batchData);
                    $stats['processed'] += count($batchData);
                    $batchData = [];
                }
            }

            if (count($batchData) > 0) {
                DB::table('sales')->insertOrIgnore($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats;
        } catch (Throwable $e) {
            Log::error("Import Sales Error: " . $e->getMessage());
            throw $e;
        }
    }
}