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
            // Baca file tanpa header (baris 1 dianggap header)
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

                // --- MAPPING KOLOM (Sesuai sales.xlsx) ---
                // 0: Sales (Nama)
                // 1: Divisi
                // 2: Status Active / Inactive
                // 3: Target IMS
                // 4: Target OA
                // 5: City

                $salesName = isset($row[0]) ? trim((string)$row[0]) : '';
                
                // Skip jika Nama Sales kosong atau Header
                if ($salesName === '' || strcasecmp($salesName, 'Sales') === 0) {
                    $stats['skipped_empty']++;
                    continue;
                }

                $batchData[] = [
                    'sales_name'  => $salesName,
                    'divisi'      => isset($row[1]) ? trim((string)$row[1]) : '',
                    'status'      => isset($row[2]) ? trim((string)$row[2]) : 'Active',
                    
                    // Bersihkan format angka
                    'target_ims'  => $this->fastNum(isset($row[3]) ? $row[3] : 0),
                    'target_oa'   => $this->fastNum(isset($row[4]) ? $row[4] : 0),
                    
                    'city'        => isset($row[5]) ? trim((string)$row[5]) : '',
                    
                    'created_at'  => $now,
                    'updated_at'  => $now,
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
            throw $e;
        }
    }

    private function processBatch(array $data)
    {
        if (empty($data)) return;
        // Gunakan INSERT agar cepat. 
        // Jika ingin update data lama, kita bisa kosongkan tabel dulu di Controller sebelum import.
        DB::table('sales')->insert($data);
    }

    // Helper untuk membersihkan angka (hapus koma ribuan jika ada)
    private function fastNum($val)
    {
        if (is_numeric($val)) return $val;
        if (!$val || $val === '-') return 0;
        // Hapus koma (pemisah ribuan)
        return str_replace([','], '', $val);
    }
}