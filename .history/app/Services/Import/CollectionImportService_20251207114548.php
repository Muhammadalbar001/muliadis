<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class CollectionImportService
{
    public function handle(string $filePath)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);
        DB::disableQueryLog();

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();
            $stats = ['total_rows' => 0, 'processed' => 0];
            $batchSize = 1000;
            $batchData = [];
            $now = date('Y-m-d H:i:s');

            // Fill Down Variables
            $lastCabang = null;
            $lastReceiveNo = null;
            $lastStatus = null;
            $lastTanggal = null;
            $lastPenagih = null;

            foreach ($reader->getRows() as $rawRow) {
                $stats['total_rows']++;
                $row = array_values($rawRow);

                $cabangRaw = $this->getStr($row, 0);
                $receiveNoRaw = $this->getStr($row, 1);

                if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                // Logic Fill Down
                if ($receiveNoRaw !== '') {
                    $lastReceiveNo = $receiveNoRaw;
                    $lastCabang = $cabangRaw ?: $lastCabang;
                    $lastStatus = $this->getStr($row, 2);
                    $lastTanggal = $this->parseDateRobust($this->getStr($row, 3));
                    $lastPenagih = $this->getStr($row, 4);
                }

                $finalReceiveNo = $receiveNoRaw ?: $lastReceiveNo;
                if (empty($finalReceiveNo)) continue;

                $batchData[] = [
                    'cabang'         => $cabangRaw ?: $lastCabang,
                    'receive_no'     => $finalReceiveNo,
                    'status'         => $this->getStr($row, 2) ?: $lastStatus,
                    'tanggal'        => $lastTanggal, // Fill Down
                    'penagih'        => $this->getStr($row, 4) ?: $lastPenagih,
                    'invoice_no'     => $this->getStr($row, 5),
                    'code_customer'  => $this->getStr($row, 6),
                    'outlet_name'    => $this->getStr($row, 7),
                    'sales_name'     => $this->getStr($row, 8),
                    'receive_amount' => $this->getStr($row, 9),
                    
                    'created_at' => $now, 'updated_at' => $now
                ];

                if (count($batchData) >= $batchSize) {
                    $this->processBatch($batchData);
                    $stats['processed'] += count($batchData);
                    $batchData = [];
                }
            }

            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['processed'] += count($batchData);
            }
            return $stats;
        } catch (Throwable $e) { Log::error("Collection Import Error: ".$e->getMessage()); throw $e; }
    }

    private function processBatch(array $data) {
        DB::table('collections')->insertOrIgnore($data);
    }
    
    // ... (Gunakan helper getStr & parseDateRobust yang sama persis seperti di atas) ...
    private function getStr($row, $index) {
        if (!isset($row[$index])) return '';
        $str = trim((string)$row[$index]);
        if (str_starts_with($str, "'")) $str = substr($str, 1);
        return $str;
    }
    private function parseDateRobust($value) {
        if (!$value || $value === '-' || $value === 'Blank') return null;
        $clean = trim((string)$value);
        if (str_starts_with($clean, "'")) $clean = substr($clean, 1);
        try {
            if (is_numeric($clean)) return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($clean)->format('Y-m-d');
            return Carbon::parse($clean)->format('Y-m-d');
        } catch (Throwable $e) { return null; }
    }
}