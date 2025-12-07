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
        // Konfigurasi Memori & Waktu (Penting untuk file besar 88rb baris)
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

            $batchSize = 2000; // Batch lebih besar agar lebih cepat
            $batchData = [];
            $now       = date('Y-m-d H:i:s');

            // --- VARIABLE FILL DOWN (Untuk mengisi sel yang di-merge) ---
            $lastCabang     = null;
            $lastTransNo    = null;
            $lastStatus     = null;
            $lastTgl        = null; // Ini yang kemarin kosong
            $lastPeriod     = null;
            $lastJatuhTempo = null;
            $lastKodePel    = null;
            $lastNamaPel    = null;

            foreach ($reader->getRows() as $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values($rawRow);

                    // 1. AMBIL DATA KUNCI
                    $cabangRaw   = $this->getStr($row, 0);
                    $transNoRaw  = $this->getStr($row, 1);
                    $kodeItem    = $this->getStr($row, 8); 

                    // Skip Baris Judul Excel
                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                    // 2. LOGIKA FILL DOWN (HEADER INVOICE)
                    if ($transNoRaw !== '') {
                        // Jika ada No Transaksi, ini adalah Baris Header
                        // Kita simpan datanya untuk dipakai baris-baris barang di bawahnya
                        $lastTransNo    = $transNoRaw;
                        $lastCabang     = $cabangRaw ?: $lastCabang;
                        $lastStatus     = $this->getStr($row, 2);
                        $lastPeriod     = $this->getStr($row, 4);
                        $lastKodePel    = $this->getStr($row, 6);
                        $lastNamaPel    = $this->getStr($row, 7);
                        
                        // Parsing Tanggal (Header) - Extra Robust
                        $tglRaw = $this->getStr($row, 3); // Kolom 3: Penjualan/Tanggal
                        $lastTgl = $this->parseDateRobust($tglRaw); // Simpan hasil parsing

                        $jtRaw = $this->getStr($row, 5);
                        $lastJatuhTempo = $this->parseDateRobust($jtRaw);
                    } 
                    
                    // Gunakan data terakhir (Fill Down)
                    $finalTransNo = $transNoRaw ?: $lastTransNo;
                    $finalTgl     = ($transNoRaw !== '' ? $lastTgl : $lastTgl); // Pastikan tanggal terisi

                    // Validasi: Transaksi Kosong -> Skip
                    if (empty($finalTransNo)) {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // Validasi: Kode Item Kosong -> Skip (Biasanya baris total/kosong di excel)
                    if (empty($kodeItem)) {
                        continue; 
                    }

                    // 3. MAPPING DATA (51 KOLOM)
                    $batchData[] = [
                        // Identitas (Pakai variable fill down)
                        'cabang'            => $cabangRaw ?: $lastCabang, 
                        'trans_no'          => $finalTransNo,
                        'status'            => $this->getStr($row, 2) ?: $lastStatus,
                        'tgl_penjualan'     => $finalTgl, // Pastikan ini terisi!
                        'period'            => $this->getStr($row, 4) ?: $lastPeriod,
                        'jatuh_tempo'       => $this->getDate($row, 5) ?: $lastJatuhTempo,
                        'kode_pelanggan'    => $this->getStr($row, 6) ?: $lastKodePel,
                        'nama_pelanggan'    => $this->getStr($row, 7) ?: $lastNamaPel,

                        // Item Detail
                        'kode_item'         => $kodeItem,
                        'sku'               => $this->getStr($row, 9),
                        'no_batch'          => $this->getStr($row, 10),
                        'ed'                => $this->getDate($row, 11),
                        'nama_item'         => $this->getStr($row, 12),

                        // Kuantitas & Nilai
                        'qty'               => $this->getStr($row, 13),
                        'satuan_jual'       => $this->getStr($row, 14),
                        'qty_i'             => $this->getStr($row, 15),
                        'satuan_i'          => $this->getStr($row, 16),
                        'nilai'             => $this->getStr($row, 17),
                        'rata2'             => $this->getStr($row, 18),
                        'up_percent'        => $this->getStr($row, 19),
                        'nilai_up'          => $this->getStr($row, 20),
                        'nilai_jual_pembulatan' => $this->getStr($row, 21),

                        // Diskon
                        'd1'                => $this->getStr($row, 22),
                        'd2'                => $this->getStr($row, 23),
                        'diskon_1'          => $this->getStr($row, 24),
                        'diskon_2'          => $this->getStr($row, 25),
                        'diskon_bawah'      => $this->getStr($row, 26),
                        'total_diskon'      => $this->getStr($row, 27),

                        // Total
                        'nilai_jual_net'    => $this->getStr($row, 28),
                        'total_harga_jual'  => $this->getStr($row, 29),
                        'ppn_head'          => $this->getStr($row, 30),
                        'total_grand'       => $this->getStr($row, 31),
                        'ppn_value'         => $this->getStr($row, 32),
                        'total_min_ppn'     => $this->getStr($row, 33),
                        'margin'            => $this->getStr($row, 34),

                        // Meta
                        'pembayaran'        => $this->getStr($row, 35),
                        'cash_bank'         => $this->getStr($row, 36),
                        'kode_sales'        => $this->getStr($row, 37),
                        'sales_name'        => $this->getStr($row, 38),
                        'supplier'          => $this->getStr($row, 39),
                        'status_pay'        => $this->getStr($row, 40),
                        'trx_id'            => $this->getStr($row, 41), // Kolom ID dari Excel
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
                    $stats['skipped_error']++;
                }
            }

            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['processed'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error("Import Error: " . $e->getMessage());
            throw $e;
        }
    }

    private function processBatch(array $data)
    {
        if (empty($data)) return;
        
        // GANTI KE INSERT BIASA (Agar semua baris masuk, termasuk duplikat item)
        // Tidak menggunakan Upsert agar data sesuai persis jumlah baris Excel
        DB::table('penjualans')->insert($data);
    }

    // --- HELPERS ---

    private function getStr(array &$row, int $index): string
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];
        if (is_null($v)) return '';
        
        $str = trim((string)$v);
        // Bersihkan tanda petik di awal (Excel text format)
        if (str_starts_with($str, "'")) $str = substr($str, 1);
        
        return $str;
    }

    // Fungsi Parsing Tanggal yang Lebih Kuat
    private function parseDateRobust($value): ?string
    {
        if (!$value || $value === '-' || $value === 'Blank') return null;
        
        // Bersihkan tanda petik dulu
        $cleanValue = trim((string)$value);
        if (str_starts_with($cleanValue, "'")) $cleanValue = substr($cleanValue, 1);

        try {
            // 1. Coba Format Excel Numeric (Contoh: 45321)
            if (is_numeric($cleanValue)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cleanValue)->format('Y-m-d');
            }

            // 2. Coba Parse String Standar (Y-m-d)
            // Menggunakan Carbon agar lebih pintar mendeteksi format
            return Carbon::parse($cleanValue)->format('Y-m-d');

        } catch (Throwable $e) {
            // Jika gagal, kembalikan null agar tidak error
            return null; 
        }
    }

    // Helper lama untuk kompatibilitas
    private function getDate(array &$row, int $index): ?string
    {
        $v = $this->getStr($row, $index);
        return $this->parseDateRobust($v);
    }
}