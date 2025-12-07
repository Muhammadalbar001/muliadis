<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class ReturImportService
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

            // --- VARIABLES FILL DOWN ---
            $lastCabang = null;
            $lastNoRetur = null;
            $lastStatus = null;
            $lastTgl = null;
            $lastNoInv = null;
            $lastKodePel = null;
            $lastNamaPel = null;

            foreach ($reader->getRows() as $rawRow) {
                $stats['total_rows']++;
                $row = array_values($rawRow);

                $cabangRaw = $this->getStr($row, 0);
                $noReturRaw = $this->getStr($row, 1);
                
                // Skip Header
                if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                // --- LOGIC FILL DOWN ---
                if ($noReturRaw !== '') {
                    $lastNoRetur = $noReturRaw;
                    $lastCabang  = $cabangRaw ?: $lastCabang;
                    $lastStatus  = $this->getStr($row, 2);
                    $lastTgl     = $this->parseDateRobust($this->getStr($row, 3));
                    $lastNoInv   = $this->getStr($row, 4);
                    $lastKodePel = $this->getStr($row, 5);
                    $lastNamaPel = $this->getStr($row, 6);
                }

                $finalNoRetur = $noReturRaw ?: $lastNoRetur;
                
                // Validasi: Kalau tidak ada No Retur, skip
                if (empty($finalNoRetur)) continue;

                // Mapping Data (39 Kolom - Sesuai View)
                $batchData[] = [
                    'cabang'            => $cabangRaw ?: $lastCabang,
                    'no_retur'          => $finalNoRetur,
                    'status'            => $this->getStr($row, 2) ?: $lastStatus,
                    'tgl_retur'         => $lastTgl, // Tanggal Fill Down
                    'no_inv'            => $this->getStr($row, 4) ?: $lastNoInv,
                    'kode_pelanggan'    => $this->getStr($row, 5) ?: $lastKodePel,
                    'nama_pelanggan'    => $this->getStr($row, 6) ?: $lastNamaPel,
                    
                    'kode_item'         => $this->getStr($row, 7),
                    'nama_item'         => $this->getStr($row, 8),
                    'qty'               => $this->getStr($row, 9),
                    'satuan_retur'      => $this->getStr($row, 10),
                    'nilai'             => $this->getStr($row, 11),
                    'rata2'             => $this->getStr($row, 12),
                    'up_percent'        => $this->getStr($row, 13),
                    'nilai_up'          => $this->getStr($row, 14),
                    'nilai_retur_pembulatan' => $this->getStr($row, 15),
                    
                    'd1' => $this->getStr($row, 16), 'd2' => $this->getStr($row, 17),
                    'diskon_1' => $this->getStr($row, 18), 'diskon_2' => $this->getStr($row, 19),
                    'diskon_bawah' => $this->getStr($row, 20), 'total_diskon' => $this->getStr($row, 21),
                    
                    'nilai_retur_net'   => $this->getStr($row, 22),
                    'total_harga_retur' => $this->getStr($row, 23),
                    'ppn_head'          => $this->getStr($row, 24),
                    'total_grand'       => $this->getStr($row, 25),
                    'ppn_value'         => $this->getStr($row, 26),
                    'total_min_ppn'     => $this->getStr($row, 27),
                    'margin'            => $this->getStr($row, 28),
                    
                    'pembayaran'        => $this->getStr($row, 29),
                    'sales_name'        => $this->getStr($row, 30),
                    'supplier'          => $this->getStr($row, 31),
                    'year'              => $this->getStr($row, 32),
                    'month'             => $this->getStr($row, 33),
                    'divisi'            => $this->getStr($row, 34),
                    'program'           => $this->getStr($row, 35),
                    'city_code'         => $this->getStr($row, 36),
                    'mother_sku'        => $this->getStr($row, 37),
                    'last_suppliers'    => $this->getStr($row, 38),

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

        } catch (Throwable $e) {
            Log::error("Import Retur Error: " . $e->getMessage());
            throw $e;
        }
    }

    private function processBatch(array $data) {
        DB::table('returs')->insertOrIgnore($data); // Gunakan InsertOrIgnore
    }

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