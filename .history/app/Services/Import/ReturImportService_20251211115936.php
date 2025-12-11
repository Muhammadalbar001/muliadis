<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReturImportService
{
    public function handle(string $filePath, bool $resetData = false)
    {
        // 1. Setup Resource (Unlimited Memory & Time untuk File Besar)
        ini_set('memory_limit', '-1'); 
        ini_set('max_execution_time', 0);
        DB::disableQueryLog(); // Matikan log query agar hemat RAM

        try {
            // Hapus Data Lama Jika Diminta
            if ($resetData) {
                DB::table('returs')->truncate();
            }

            // 2. Setup Reader (Tanpa Header Row -> Array Index Numerik)
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'      => 0,
                'processed'       => 0,
                'skipped_empty'   => 0,
                'skipped_no_item' => 0,
            ];

            $batchSize = 1000; // Ukuran Chunk Insert
            $batchData = [];
            $now       = now(); // Cache waktu sekarang

            // --- Fill Down Memory Variables ---
            // Variabel ini menyimpan nilai baris sebelumnya jika baris sekarang kosong (merged cell)
            $lastCabang  = null;
            $lastNoRetur = null;
            $lastStatus  = null;
            $lastTgl     = null;
            $lastNoInv   = null;
            $lastKodePel = null;
            $lastNamaPel = null;
            $lastSales   = null;
            $lastSupp    = null;

            foreach ($reader->getRows() as $rawRow) {
                $stats['total_rows']++;
                
                // Konversi row ke array index numerik (lebih cepat daripada assoc array)
                $row = array_values($rawRow);

                // Ambil Data Kunci (Sesuaikan index dengan kolom Excel Anda)
                // Kolom 0: Cabang, Kolom 1: No Retur
                $cabangRaw  = isset($row[0]) ? trim((string)$row[0]) : '';
                $noReturRaw = isset($row[1]) ? trim((string)$row[1]) : '';
                $kodeItem   = isset($row[7]) ? trim((string)$row[7]) : ''; // Kolom H (Index 7)

                // Skip Header (Baris Judul)
                if (strtolower($cabangRaw) === 'cabang' || strtolower($noReturRaw) === 'no retur') continue;

                // --- LOGIKA FILL DOWN (Mengisi cell kosong dari atasnya) ---
                if ($noReturRaw !== '') {
                    // Jika ada No Retur baru, update semua variable fill down
                    $lastNoRetur = $noReturRaw;
                    $lastCabang  = $cabangRaw ?: $lastCabang;
                    $lastStatus  = isset($row[2]) ? trim((string)$row[2]) : null;
                    
                    // Parse Tanggal (Kolom 3)
                    $tglStr  = isset($row[3]) ? trim((string)$row[3]) : '';
                    $lastTgl = $this->fastDateParse($tglStr);

                    $lastNoInv   = isset($row[4]) ? trim((string)$row[4]) : null;
                    $lastKodePel = isset($row[5]) ? trim((string)$row[5]) : null;
                    $lastNamaPel = isset($row[6]) ? trim((string)$row[6]) : null;
                    
                    // Sales & Supplier (Biasanya di kolom belakang, sesuaikan index)
                    $lastSales = isset($row[30]) ? trim((string)$row[30]) : null;
                    $lastSupp  = isset($row[31]) ? trim((string)$row[31]) : null;
                }

                $finalNoRetur = $noReturRaw ?: $lastNoRetur;

                // Validasi 1: Pastikan ada No Retur (Header transaksi)
                if (empty($finalNoRetur)) {
                    $stats['skipped_empty']++;
                    continue;
                }

                // Validasi 2: Pastikan ada Kode Item (Detail barang)
                // Jika kode item kosong, biasanya ini baris "Total" atau footer -> Skip
                if (empty($kodeItem)) {
                    $stats['skipped_no_item']++;
                    continue;
                }

                // --- MAPPING DATA ---
                $batchData[] = [
                    'cabang'           => $cabangRaw ?: $lastCabang,
                    'no_retur'         => $finalNoRetur,
                    'status'           => isset($row[2]) ? trim((string)$row[2]) : $lastStatus,
                    'tgl_retur'        => $lastTgl, 
                    'no_inv'           => isset($row[4]) ? trim((string)$row[4]) : $lastNoInv,
                    'kode_pelanggan'   => isset($row[5]) ? trim((string)$row[5]) : $lastKodePel,
                    'nama_pelanggan'   => isset($row[6]) ? trim((string)$row[6]) : $lastNamaPel,
                    
                    'kode_item'        => $kodeItem,
                    'nama_item'        => isset($row[8]) ? trim((string)$row[8]) : '',
                    
                    // Angka-angka (Gunakan fastNum agar aman)
                    'qty'              => $this->fastNum(isset($row[9]) ? $row[9] : 0),
                    'satuan_retur'     => isset($row[10]) ? trim((string)$row[10]) : '',
                    'nilai'            => $this->fastNum(isset($row[11]) ? $row[11] : 0),
                    'rata2'            => $this->fastNum(isset($row[12]) ? $row[12] : 0),
                    'up_percent'       => $this->fastNum(isset($row[13]) ? $row[13] : 0),
                    'nilai_up'         => $this->fastNum(isset($row[14]) ? $row[14] : 0),
                    'nilai_retur_pembulatan' => $this->fastNum(isset($row[15]) ? $row[15] : 0),
                    
                    'd1'               => $this->fastNum(isset($row[16]) ? $row[16] : 0),
                    'd2'               => $this->fastNum(isset($row[17]) ? $row[17] : 0),
                    'diskon_1'         => $this->fastNum(isset($row[18]) ? $row[18] : 0),
                    'diskon_2'         => $this->fastNum(isset($row[19]) ? $row[19] : 0),
                    'diskon_bawah'     => $this->fastNum(isset($row[20]) ? $row[20] : 0),
                    'total_diskon'     => $this->fastNum(isset($row[21]) ? $row[21] : 0),
                    
                    'nilai_retur_net'  => $this->fastNum(isset($row[22]) ? $row[22] : 0),
                    'total_harga_retur'=> $this->fastNum(isset($row[23]) ? $row[23] : 0),
                    'ppn_head'         => $this->fastNum(isset($row[24]) ? $row[24] : 0),
                    'total_grand'      => $this->fastNum(isset($row[25]) ? $row[25] : 0),
                    'ppn_value'        => $this->fastNum(isset($row[26]) ? $row[26] : 0),
                    'total_min_ppn'    => $this->fastNum(isset($row[27]) ? $row[27] : 0),
                    'margin'           => $this->fastNum(isset($row[28]) ? $row[28] : 0),
                    
                    'pembayaran'       => isset($row[29]) ? trim((string)$row[29]) : '',
                    'sales_name'       => isset($row[30]) ? trim((string)$row[30]) : $lastSales,
                    'supplier'         => isset($row[31]) ? trim((string)$row[31]) : $lastSupp,
                    'year'             => isset($row[32]) ? trim((string)$row[32]) : '',
                    'month'            => isset($row[33]) ? trim((string)$row[33]) : '',
                    'divisi'           => isset($row[34]) ? trim((string)$row[34]) : '',
                    'program'          => isset($row[35]) ? trim((string)$row[35]) : '',
                    'city_code'        => isset($row[36]) ? trim((string)$row[36]) : '',
                    'mother_sku'       => isset($row[37]) ? trim((string)$row[37]) : '',
                    'last_suppliers'   => isset($row[38]) ? trim((string)$row[38]) : '',

                    'created_at' => $now,
                    'updated_at' => $now
                ];

                // INSERT CHUNK (Per 1000 Baris)
                if (count($batchData) >= $batchSize) {
                    DB::table('returs')->insert($batchData);
                    $stats['processed'] += count($batchData);
                    $batchData = []; // Kosongkan memori
                }
            }

            // INSERT SISA DATA TERAKHIR
            if (count($batchData) > 0) {
                DB::table('returs')->insert($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats; // Return array statistik

        } catch (Throwable $e) {
            Log::error("Retur Import Error: " . $e->getMessage());
            throw $e;
        }
    }

    // Helper: Parse Date Cepat (Tanpa Carbon Object di loop)
    private function fastDateParse($val)
    {
        if (!$val || $val === '-' || $val === 'Blank') return null;
        if (str_starts_with($val, "'")) $val = substr($val, 1);

        // Excel Numeric Date
        if (is_numeric($val)) {
            $unixDate = ($val - 25569) * 86400;
            return gmdate("Y-m-d", $unixDate);
        }

        // String Y-m-d
        $ts = strtotime($val);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    // Helper: Parse Number Cepat
    private function fastNum($val)
    {
        if (is_numeric($val)) return $val;
        if (!$val || $val === '-') return 0;
        return str_replace([',', ' '], '', $val);
    }
}