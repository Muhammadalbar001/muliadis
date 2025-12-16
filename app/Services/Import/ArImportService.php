<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class ArImportService
{
    public function handle(string $filePath, bool $resetData = false) 
    {
        ini_set('memory_limit', '512M'); 
        ini_set('max_execution_time', 0);
        DB::disableQueryLog();

        try {
            if ($resetData) {
                DB::table('account_receivables')->truncate();
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

            // --- VARIABLES FILL DOWN ---
            $lastCabang = null;

            foreach ($reader->getRows() as $rawRow) {
                $stats['total_rows']++;
                $row = array_values($rawRow);

                // Ambil Data
                $cabangRaw = isset($row[0]) ? trim((string)$row[0]) : '';
                $noInvRaw  = isset($row[1]) ? trim((string)$row[1]) : ''; // No Penjualan

                // Skip Header
                if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                // Logic Fill Down (Hanya Cabang yang biasanya di-merge di AR)
                // Jika No Invoice ada, update Last Cabang
                if ($cabangRaw !== '') {
                    $lastCabang = $cabangRaw;
                }

                // Validasi: No Penjualan Wajib Ada
                if ($noInvRaw === '') {
                    $stats['skipped_empty']++;
                    continue;
                }

                // --- MAPPING DATA (25 Kolom) ---
                $batchData[] = [
                    'cabang'         => $cabangRaw ?: $lastCabang,
                    'no_penjualan'   => $noInvRaw,
                    'pelanggan_code' => isset($row[2]) ? trim((string)$row[2]) : '',
                    'pelanggan_name' => isset($row[3]) ? trim((string)$row[3]) : '',
                    'sales_name'     => isset($row[4]) ? trim((string)$row[4]) : '',
                    'info'           => isset($row[5]) ? trim((string)$row[5]) : '',
                    
                    'total_nilai'    => $this->fastNum(isset($row[6]) ? $row[6] : 0),
                    'nilai'          => $this->fastNum(isset($row[7]) ? $row[7] : 0), // Sisa Piutang
                    
                    'tgl_penjualan'  => $this->fastDateParse(isset($row[8]) ? $row[8] : ''),
                    'tgl_antar'      => $this->fastDateParse(isset($row[9]) ? $row[9] : ''),
                    'status_antar'   => isset($row[10]) ? trim((string)$row[10]) : '',
                    'jatuh_tempo'    => $this->fastDateParse(isset($row[11]) ? $row[11] : ''),
                    
                    'current'        => $this->fastNum(isset($row[12]) ? $row[12] : 0),
                    'le_15_days'     => $this->fastNum(isset($row[13]) ? $row[13] : 0),
                    'bt_16_30_days'  => $this->fastNum(isset($row[14]) ? $row[14] : 0),
                    'gt_30_days'     => $this->fastNum(isset($row[15]) ? $row[15] : 0),
                    
                    'status'         => isset($row[16]) ? trim((string)$row[16]) : '',
                    'alamat'         => isset($row[17]) ? trim((string)$row[17]) : '',
                    'phone'          => isset($row[18]) ? trim((string)$row[18]) : '',
                    'umur_piutang'   => isset($row[19]) ? trim((string)$row[19]) : '',
                    'unique_id'      => isset($row[20]) ? trim((string)$row[20]) : '',
                    
                    'lt_14_days'     => $this->fastNum(isset($row[21]) ? $row[21] : 0),
                    'bt_14_30_days'  => $this->fastNum(isset($row[22]) ? $row[22] : 0),
                    'up_30_days'     => $this->fastNum(isset($row[23]) ? $row[23] : 0),
                    'range_piutang'  => isset($row[24]) ? trim((string)$row[24]) : '',
                    
                    'created_at'     => $now,
                    'updated_at'     => $now
                ];

                // Insert Chunk
                if (count($batchData) >= $batchSize) {
                    $this->processBatch($batchData);
                    $stats['processed'] += count($batchData);
                    $batchData = [];
                }
            }

            // Insert Sisa
            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error("Import AR Error: " . $e->getMessage());
            throw $e;
        }
    }

    private function processBatch(array $data)
    {
        if (empty($data)) return;
        DB::table('account_receivables')->insert($data);
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