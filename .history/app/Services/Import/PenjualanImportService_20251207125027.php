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
            // Baca tanpa header row
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'       => 0,
                'processed'        => 0,
                'skipped_empty'    => 0,
                'skipped_no_item'  => 0,
            ];

            $batchSize = 1000; // Ukuran chunk yang optimal
            $batchData = [];
            $now       = now(); // Cache waktu sekarang

            // Fill Down Variables
            $lastCabang     = null;
            $lastTransNo    = null;
            $lastStatus     = null;
            $lastTgl        = null;
            $lastPeriod     = null;
            $lastJatuhTempo = null;
            $lastKodePel    = null;
            $lastNamaPel    = null;

            foreach ($reader->getRows() as $rawRow) {
                $stats['total_rows']++;
                
                // Konversi row ke array index numerik agar lebih cepat aksesnya
                $row = array_values($rawRow);

                // Akses langsung index array (lebih cepat dari helper function berulang)
                $cabangRaw   = isset($row[0]) ? trim((string)$row[0]) : '';
                $transNoRaw  = isset($row[1]) ? trim((string)$row[1]) : '';
                $kodeItem    = isset($row[8]) ? trim((string)$row[8]) : '';

                // Skip Header
                if ($cabangRaw === 'Cabang' || $cabangRaw === 'cabang') continue;

                // --- LOGIKA FILL DOWN ---
                if ($transNoRaw !== '') {
                    $lastTransNo    = $transNoRaw;
                    $lastCabang     = $cabangRaw ?: $lastCabang;
                    $lastStatus     = isset($row[2]) ? trim((string)$row[2]) : null;
                    
                    // Parse Tanggal (Manual in-line parsing untuk kecepatan)
                    $tglStr = isset($row[3]) ? trim((string)$row[3]) : '';
                    if (str_starts_with($tglStr, "'")) $tglStr = substr($tglStr, 1);
                    $lastTgl = $this->fastDateParse($tglStr);

                    $lastPeriod     = isset($row[4]) ? trim((string)$row[4]) : null;
                    
                    $jtStr = isset($row[5]) ? trim((string)$row[5]) : '';
                    if (str_starts_with($jtStr, "'")) $jtStr = substr($jtStr, 1);
                    $lastJatuhTempo = $this->fastDateParse($jtStr);

                    $lastKodePel    = isset($row[6]) ? trim((string)$row[6]) : null;
                    $lastNamaPel    = isset($row[7]) ? trim((string)$row[7]) : null;
                }

                $finalTransNo = $transNoRaw ?: $lastTransNo;

                // Validasi Cepat
                if (empty($finalTransNo)) {
                    $stats['skipped_empty']++;
                    continue;
                }
                if (empty($kodeItem)) {
                    $stats['skipped_no_item']++;
                    continue;
                }

                // --- MAPPING DATA (Optimized) ---
                $batchData[] = [
                    'cabang'            => $cabangRaw ?: $lastCabang,
                    'trans_no'          => $finalTransNo,
                    'status'            => isset($row[2]) ? trim((string)$row[2]) : $lastStatus,
                    'tgl_penjualan'     => $lastTgl,
                    'period'            => isset($row[4]) ? trim((string)$row[4]) : $lastPeriod,
                    'jatuh_tempo'       => $lastJatuhTempo,
                    'kode_pelanggan'    => isset($row[6]) ? trim((string)$row[6]) : $lastKodePel,
                    'nama_pelanggan'    => isset($row[7]) ? trim((string)$row[7]) : $lastNamaPel,

                    'kode_item'         => $kodeItem,
                    'sku'               => isset($row[9]) ? trim((string)$row[9]) : '',
                    'no_batch'          => isset($row[10]) ? trim((string)$row[10]) : '',
                    'ed'                => $this->fastDateParse(isset($row[11]) ? trim((string)$row[11]) : ''),
                    'nama_item'         => isset($row[12]) ? trim((string)$row[12]) : '',

                    // Angka (Pastikan numeric)
                    'qty'               => $this->fastNum(isset($row[13]) ? $row[13] : 0),
                    'satuan_jual'       => isset($row[14]) ? trim((string)$row[14]) : '',
                    'qty_i'             => $this->fastNum(isset($row[15]) ? $row[15] : 0),
                    'satuan_i'          => isset($row[16]) ? trim((string)$row[16]) : '',
                    'nilai'             => $this->fastNum(isset($row[17]) ? $row[17] : 0),
                    'rata2'             => $this->fastNum(isset($row[18]) ? $row[18] : 0),
                    'up_percent'        => $this->fastNum(isset($row[19]) ? $row[19] : 0),
                    'nilai_up'          => $this->fastNum(isset($row[20]) ? $row[20] : 0),
                    'nilai_jual_pembulatan' => $this->fastNum(isset($row[21]) ? $row[21] : 0),

                    'd1'                => $this->fastNum(isset($row[22]) ? $row[22] : 0),
                    'd2'                => $this->fastNum(isset($row[23]) ? $row[23] : 0),
                    'diskon_1'          => $this->fastNum(isset($row[24]) ? $row[24] : 0),
                    'diskon_2'          => $this->fastNum(isset($row[25]) ? $row[25] : 0),
                    'diskon_bawah'      => $this->fastNum(isset($row[26]) ? $row[26] : 0),
                    'total_diskon'      => $this->fastNum(isset($row[27]) ? $row[27] : 0),

                    'nilai_jual_net'    => $this->fastNum(isset($row[28]) ? $row[28] : 0),
                    'total_harga_jual'  => $this->fastNum(isset($row[29]) ? $row[29] : 0),
                    'ppn_head'          => $this->fastNum(isset($row[30]) ? $row[30] : 0),
                    'total_grand'       => $this->fastNum(isset($row[31]) ? $row[31] : 0),
                    'ppn_value'         => $this->fastNum(isset($row[32]) ? $row[32] : 0),
                    'total_min_ppn'     => $this->fastNum(isset($row[33]) ? $row[33] : 0),
                    'margin'            => $this->fastNum(isset($row[34]) ? $row[34] : 0),

                    'pembayaran'        => isset($row[35]) ? trim((string)$row[35]) : '',
                    'cash_bank'         => isset($row[36]) ? trim((string)$row[36]) : '',
                    'kode_sales'        => isset($row[37]) ? trim((string)$row[37]) : '',
                    'sales_name'        => isset($row[38]) ? trim((string)$row[38]) : '',
                    'supplier'          => isset($row[39]) ? trim((string)$row[39]) : '',
                    'status_pay'        => isset($row[40]) ? trim((string)$row[40]) : '',
                    'trx_id'            => isset($row[41]) ? trim((string)$row[41]) : '',
                    'year'              => isset($row[42]) ? trim((string)$row[42]) : '',
                    'month'             => isset($row[43]) ? trim((string)$row[43]) : '',
                    'last_suppliers'    => isset($row[44]) ? trim((string)$row[44]) : '',
                    'mother_sku'        => isset($row[45]) ? trim((string)$row[45]) : '',
                    'divisi'            => isset($row[46]) ? trim((string)$row[46]) : '',
                    'program'           => isset($row[47]) ? trim((string)$row[47]) : '',
                    'outlet_code_sales_name'   => isset($row[48]) ? trim((string)$row[48]) : '',
                    'city_code_outlet_program' => isset($row[49]) ? trim((string)$row[49]) : '',
                    'sales_name_outlet_code'   => isset($row[50]) ? trim((string)$row[50]) : '',

                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // INSERT CHUNK
                if (count($batchData) >= $batchSize) {
                    DB::table('penjualans')->insert($batchData); // INSERT BIASA (Lebih cepat dari insertOrIgnore jika data sudah bersih)
                    $stats['processed'] += count($batchData);
                    $batchData = []; // Kosongkan
                }
            }

            // INSERT SISA DATA
            if (count($batchData) > 0) {
                DB::table('penjualans')->insert($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error("Import Error: " . $e->getMessage());
            throw $e;
        }
    }

    // --- FAST HELPER (Tanpa Carbon untuk Loop berat) ---
    // Carbon object creation di loop 88rb kali itu berat. Kita pakai native PHP strtotime/DateTime
    
    private function fastDateParse($val)
    {
        if (!$val || $val === '-' || $val === 'Blank') return null;
        if (str_starts_with($val, "'")) $val = substr($val, 1); // Bersihkan petik

        // Cek Excel Numeric Date
        if (is_numeric($val)) {
            // Unix timestamp dari Excel date (25569 adalah selisih hari antara 1970-01-01 dan 1900-01-01)
            $unixDate = ($val - 25569) * 86400;
            return gmdate("Y-m-d", $unixDate);
        }

        // Cek String Y-m-d
        $ts = strtotime($val);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    private function fastNum($val)
    {
        if (is_numeric($val)) return $val;
        if (!$val || $val === '-') return 0;
        // Hapus koma ribuan (1,000.00 -> 1000.00)
        return str_replace([',', ' '], '', $val);
    }
}