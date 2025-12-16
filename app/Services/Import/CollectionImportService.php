<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class CollectionImportService
{
    public function handle(string $filePath, bool $resetData = false)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
        DB::disableQueryLog();

        try {
            if ($resetData) {
                DB::table('collections')->truncate();
            }
            
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'       => 0,
                'processed'        => 0,
                'skipped_empty'    => 0,
            ];

            $batchSize = 500;
            $batchData = [];
            $now       = now();

            // --- Fill Down Memory ---
            $lastCabang    = null;
            $lastReceiveNo = null;
            $lastStatus    = null;
            $lastTanggal   = null;
            $lastPenagih   = null;

            foreach ($reader->getRows() as $rawRow) {
                $stats['total_rows']++;
                $row = array_values($rawRow);

                $cabangRaw    = isset($row[0]) ? trim((string)$row[0]) : '';
                $receiveNoRaw = isset($row[1]) ? trim((string)$row[1]) : '';
                $invoiceNo    = isset($row[5]) ? trim((string)$row[5]) : '';

                if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                // --- LOGIKA FILL DOWN ---
                if ($receiveNoRaw !== '') {
                    // Header Baru Ditemukan
                    $lastReceiveNo = $receiveNoRaw;
                    $lastCabang    = $cabangRaw ?: $lastCabang;
                    $lastStatus    = isset($row[2]) ? trim((string)$row[2]) : null;
                    
                    // Parse Tanggal
                    $tglStr        = isset($row[3]) ? trim((string)$row[3]) : '';
                    $lastTanggal   = $this->fastDateParse($tglStr);

                    $lastPenagih   = isset($row[4]) ? trim((string)$row[4]) : null;
                }

                $finalReceiveNo = $receiveNoRaw ?: $lastReceiveNo;

                // Validasi: Jika Receive No tidak ada, skip
                if (empty($finalReceiveNo)) {
                    $stats['skipped_empty']++;
                    continue;
                }

                // Validasi: Jika Invoice No kosong, anggap baris total/sampah
                if (empty($invoiceNo)) {
                    continue;
                }

                // --- MAPPING DATA (10 Kolom) ---
                $batchData[] = [
                    'cabang'         => $cabangRaw ?: $lastCabang,
                    'receive_no'     => $finalReceiveNo,
                    'status'         => isset($row[2]) ? trim((string)$row[2]) : $lastStatus,
                    'tanggal'        => $lastTanggal, // Tanggal Fill Down
                    'penagih'        => isset($row[4]) ? trim((string)$row[4]) : $lastPenagih,
                    
                    'invoice_no'     => $invoiceNo,
                    'code_customer'  => isset($row[6]) ? trim((string)$row[6]) : '',
                    'outlet_name'    => isset($row[7]) ? trim((string)$row[7]) : '',
                    'sales_name'     => isset($row[8]) ? trim((string)$row[8]) : '',
                    'receive_amount' => $this->fastNum(isset($row[9]) ? $row[9] : 0),
                    
                    'created_at'     => $now,
                    'updated_at'     => $now
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

        } catch (Throwable $e) {
            Log::error("Import Collection Error: " . $e->getMessage());
            throw $e;
        }
    }

    private function processBatch(array $data)
    {
        if (empty($data)) return;
        DB::table('collections')->insert($data);
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

    private function fastNum($val)
    {
        if (is_numeric($val)) return $val;
        if (!$val || $val === '-') return 0;
        return str_replace([',', ' '], '', $val);
    }
}