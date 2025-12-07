<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class ArImportService
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
            $lastNoInv = null;
            $lastPelanggan = null;
            $lastSales = null;
            $lastTgl = null;
            $lastJatuhTempo = null;

            foreach ($reader->getRows() as $rawRow) {
                $stats['total_rows']++;
                $row = array_values($rawRow);

                $cabangRaw = $this->getStr($row, 0);
                $noInvRaw  = $this->getStr($row, 1);

                if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                // Logic Fill Down
                if ($noInvRaw !== '') {
                    $lastNoInv = $noInvRaw;
                    $lastCabang = $cabangRaw ?: $lastCabang;
                    $lastPelanggan = $this->getStr($row, 2); // Nama Pelanggan
                    // ... (Kolom identitas lainnya)
                    $lastSales = $this->getStr($row, 4);
                    $lastTgl = $this->parseDateRobust($this->getStr($row, 8));
                    $lastJatuhTempo = $this->parseDateRobust($this->getStr($row, 11));
                }

                $finalNoInv = $noInvRaw ?: $lastNoInv;
                if (empty($finalNoInv)) continue;

                $batchData[] = [
                    'cabang'         => $cabangRaw ?: $lastCabang,
                    'no_penjualan'   => $finalNoInv,
                    'pelanggan_name' => $this->getStr($row, 2) ?: $lastPelanggan,
                    'pelanggan_code' => $this->getStr($row, 3),
                    'sales_name'     => $this->getStr($row, 4) ?: $lastSales,
                    'info'           => $this->getStr($row, 5),
                    
                    'total_nilai'    => $this->getStr($row, 6),
                    'nilai'          => $this->getStr($row, 7), // Sisa
                    
                    'tgl_penjualan'  => $lastTgl, // Dari Fill Down
                    'tgl_antar'      => $this->parseDateRobust($this->getStr($row, 9)),
                    'status_antar'   => $this->getStr($row, 10),
                    'jatuh_tempo'    => $lastJatuhTempo, // Dari Fill Down
                    
                    'current'        => $this->getStr($row, 12),
                    'le_15_days'     => $this->getStr($row, 13),
                    'bt_16_30_days'  => $this->getStr($row, 14),
                    'gt_30_days'     => $this->getStr($row, 15),
                    
                    'status'         => $this->getStr($row, 16),
                    'alamat'         => $this->getStr($row, 17),
                    'phone'          => $this->getStr($row, 18),
                    'umur_piutang'   => $this->getStr($row, 19),
                    'unique_id'      => $this->getStr($row, 20),
                    
                    'lt_14_days'     => $this->getStr($row, 21),
                    'bt_14_30_days'  => $this->getStr($row, 22),
                    'up_30_days'     => $this->getStr($row, 23),
                    'range_piutang'  => $this->getStr($row, 24),
                    
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
        } catch (Throwable $e) { Log::error("AR Import Error: ".$e->getMessage()); throw $e; }
    }

    private function processBatch(array $data) {
        DB::table('account_receivables')->insertOrIgnore($data);
    }
    
    // ... (Helper getStr & parseDateRobust SAMA PERSIS dengan PenjualanImportService)
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