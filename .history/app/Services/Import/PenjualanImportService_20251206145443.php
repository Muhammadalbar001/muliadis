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
                'skipped_error'  => 0,
            ];

            $batchSize = 2000; 
            $batchData = [];
            
            // Variabel Fill Down
            $lastCabang     = null;
            $lastTransNo    = null;
            $lastStatus     = null;
            $lastTgl        = null;
            $lastPeriod     = null;
            $lastJatuhTempo = null;
            $lastKodePel    = null;
            $lastNamaPel    = null;

            $now = date('Y-m-d H:i:s');

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
                        $lastPeriod     = $this->getStr($row, 4);
                        $lastKodePel    = $this->getStr($row, 6);
                        $lastNamaPel    = $this->getStr($row, 7);
                        
                        // Parse Tanggal Header (Format Text Excel)
                        $tglRaw  = $this->getStr($row, 3);
                        $lastTgl = $this->parseDateRobust($tglRaw); 

                        $jtRaw   = $this->getStr($row, 5);
                        $lastJatuhTempo = $this->parseDateRobust($jtRaw);
                    } 
                    
                    $finalTransNo = $transNoRaw ?: $lastTransNo;
                    $finalTgl     = $lastTgl; // Selalu pakai tanggal dari header terakhir

                    // Validasi
                    if (empty($finalTransNo)) {
                        $stats['skipped_empty']++;
                        continue;
                    }
                    if (empty($kodeItem)) continue; 

                    // Mapping
                    $batchData[] = [
                        'cabang'            => $cabangRaw ?: $lastCabang, 
                        'trans_no'          => $finalTransNo,
                        'status'            => $this->getStr($row, 2) ?: $lastStatus,
                        'tgl_penjualan'     => $finalTgl, 
                        'period'            => $this->getStr($row, 4) ?: $lastPeriod,
                        'jatuh_tempo'       => $this->getDate($row, 5) ?: $lastJatuhTempo,
                        'kode_pelanggan'    => $this->getStr($row, 6) ?: $lastKodePel,
                        'nama_pelanggan'    => $this->getStr($row, 7) ?: $lastNamaPel,
                        'kode_item'         => $kodeItem,
                        'sku'               => $this->getStr($row, 9),
                        'no_batch'          => $this->getStr($row, 10),
                        'ed'                => $this->getDate($row, 11),
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

                    // --- EKSEKUSI BATCH ---
                    if (count($batchData) >= $batchSize) {
                        // PERBAIKAN UTAMA: Try-Catch Khusus Batch
                        try {
                            $this->processBatch($batchData);
                            $stats['processed'] += count($batchData);
                        } catch (Throwable $e) {
                            // Jika error (misal data kembar), kita log tapi JANGAN STOP LOOP
                            // Dan WAJIB kosongkan batchData agar tidak infinite loop
                            $stats['skipped_error'] += count($batchData);
                            Log::error("Batch Error: " . $e->getMessage());
                        }
                        $batchData = []; // Wajib dikosongkan apapun yang terjadi
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                }
            }

            // Sisa Batch Terakhir
            if (count($batchData) > 0) {
                try {
                    $this->processBatch($batchData);
                    $stats['processed'] += count($batchData);
                } catch (Throwable $e) {
                    $stats['skipped_error'] += count($batchData);
                }
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

        // insertOrIgnore: Fitur MySQL untuk 'Skip' data yang error/duplikat tanpa membatalkan proses
        // Ini sangat cepat dan aman untuk import massal.
        DB::table('penjualans')->insertOrIgnore($data);
    }

    // --- HELPERS ---
    private function getStr(array &$row, int $index): string
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];
        if (is_null($v)) return '';
        $str = trim((string)$v);
        if (str_starts_with($str, "'")) $str = substr($str, 1);
        return $str;
    }

    private function parseDateRobust($value): ?string
    {
        if (!$value || $value === '-' || $value === 'Blank') return null;
        $cleanValue = trim((string)$value);
        if (str_starts_with($cleanValue, "'")) $cleanValue = substr($cleanValue, 1);
        try {
            if (is_numeric($cleanValue)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cleanValue)->format('Y-m-d');
            }
            return Carbon::parse($cleanValue)->format('Y-m-d');
        } catch (Throwable $e) {
            return null; 
        }
    }

    private function getDate(array &$row, int $index): ?string
    {
        $v = $this->getStr($row, $index);
        return $this->parseDateRobust($v);
    }
}