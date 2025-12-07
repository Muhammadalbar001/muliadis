<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class PenjualanImportService
{
    public function handle(string $filePath)
    {
        ini_set('memory_limit', '2048M'); 
        ini_set('max_execution_time', 0);
        DB::disableQueryLog();

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'     => 0,
                'processed'      => 0,
                'skipped_empty'  => 0,
            ];

            // REVISI PENTING: Batch Size diturunkan agar tidak kena limit MySQL
            // 500 baris x 53 kolom = 26.500 placeholder (Aman, jauh di bawah 65.535)
            $batchSize = 500; 
            
            $batchData = [];
            $now       = date('Y-m-d H:i:s');

            // Variables Fill Down
            $lastCabang     = null;
            $lastTransNo    = null;
            $lastStatus     = null;
            $lastTgl        = null;
            $lastPeriod     = null;
            $lastJatuhTempo = null;
            $lastKodePel    = null;
            $lastNamaPel    = null;

            foreach ($reader->getRows() as $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values($rawRow);

                    $cabangRaw   = $this->getStr($row, 0); 
                    $transNoRaw  = $this->getStr($row, 1); 
                    $kodeItem    = $this->getStr($row, 8); 

                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                    // --- LOGIKA FILL DOWN ---
                    if ($transNoRaw !== '') {
                        $lastTransNo    = $transNoRaw;
                        $lastCabang     = $cabangRaw ?: $lastCabang; 
                        $lastStatus     = $this->getStr($row, 2);
                        
                        $lastTgl        = $this->parseDateRobust($this->getStr($row, 3));
                        $lastPeriod     = $this->getStr($row, 4);
                        $lastJatuhTempo = $this->parseDateRobust($this->getStr($row, 5));
                        
                        $lastKodePel    = $this->getStr($row, 6);
                        $lastNamaPel    = $this->getStr($row, 7);
                    }

                    $finalTransNo = $transNoRaw ?: $lastTransNo;
                    
                    if (empty($finalTransNo)) {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    if (empty($kodeItem)) {
                        continue;
                    }

                    // --- MAPPING DATA ---
                    $batchData[] = [
                        'cabang'            => $cabangRaw ?: $lastCabang, 
                        'trans_no'          => $finalTransNo,
                        'status'            => $this->getStr($row, 2) ?: $lastStatus,
                        'tgl_penjualan'     => $lastTgl,
                        'period'            => $this->getStr($row, 4) ?: $lastPeriod,
                        'jatuh_tempo'       => $lastJatuhTempo,
                        'kode_pelanggan'    => $this->getStr($row, 6) ?: $lastKodePel,
                        'nama_pelanggan'    => $this->getStr($row, 7) ?: $lastNamaPel,

                        'kode_item'         => $kodeItem,
                        'sku'               => $this->getStr($row, 9),
                        'no_batch'          => $this->getStr($row, 10),
                        'ed'                => $this->parseDateRobust($this->getStr($row, 11)),
                        'nama_item'         => $this->getStr($row, 12),

                        'qty'               => $this->getStr($row, 13),
                        'satuan_jual'       => $this->getStr($row, 14),
                        'qty_i'             => $this->getStr($row, 15),
                        'satuan_i'          => $this->getStr($row, 16),
                        'nilai'             => $this->getStr($row, 17),
                        'rata2'             => $this->getStr($row, 18),
                        'up_percent'        => $this->getStr($row, 19),
                        'nilai_up'          => $this->getStr($row, 20),
                        'nilai_jual_pembulatan' => $this->getStr($row, 21),

                        'd1'                => $this->getStr($row, 22),
                        'd2'                => $this->getStr($row, 23),
                        'diskon_1'          => $this->getStr($row, 24),
                        'diskon_2'          => $this->getStr($row, 25),
                        'diskon_bawah'      => $this->getStr($row, 26),
                        'total_diskon'      => $this->getStr($row, 27),

                        'nilai_jual_net'    => $this->getStr($row, 28),
                        'total_harga_jual'  => $this->getStr($row, 29),
                        'ppn_head'          => $this->getStr($row, 30),
                        'total_grand'       => $this->getStr($row, 31),
                        'ppn_value'         => $this->getStr($row, 32),
                        'total_min_ppn'     => $this->getStr($row, 33),
                        'margin'            => $this->getStr($row, 34),

                        'pembayaran'        => $this->getStr($row, 35),
                        'cash_bank'         => $this->getStr($row, 36),
                        'kode_sales'        => $this->getStr($row, 37),
                        'sales_name'        => $this->getStr($row, 38),
                        'supplier'          => $this->getStr($row, 39),
                        'status_pay'        => $this->getStr($row, 40),
                        'trx_id'            => $this->getStr($row, 41),
                        'year'              => $this->getStr($row, 42),
                        'month'             => $this->getStr($row, 43),
                        'last_suppliers'    => $this->getStr($row, 44),
                        'mother_sku'        => $this->getStr($row, 45),
                        'divisi'            => $this->getStr($row, 46),
                        'program'           => $this->getStr($row, 47),
                        'outlet_code_sales_name'   => $this->getStr($row, 48),
                        'city_code_outlet_program' => $this->getStr($row, 49),
                        'sales_name_outlet_code'   => $this->getStr($row, 50),

                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['processed'] += count($batchData);
                        $batchData = [];
                    }

                } catch (Throwable $e) {
                    // Skip error diam-diam
                }
            }

            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error("Import Fatal Error: " . $e->getMessage());
            throw $e;
        }
    }

    private function processBatch(array $data)
    {
        if (empty($data)) return;
        DB::table('penjualans')->insertOrIgnore($data);
    }

    private function getStr(array &$row, int $index): string
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];
        if (is_null($v)) return '';
        $str = trim((string)$v);
        if (str_starts_with($str, "'")) {
            $str = substr($str, 1);
        }
        return $str;
    }

    private function parseDateRobust($value): ?string
    {
        if (!$value || $value === '-' || $value === 'Blank') return null;
        $clean = trim((string)$value);
        if (str_starts_with($clean, "'")) $clean = substr($clean, 1);

        try {
            if (is_numeric($clean)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($clean)->format('Y-m-d');
            }
            return Carbon::parse($clean)->format('Y-m-d');
        } catch (Throwable $e) {
            return null;
        }
    }
}