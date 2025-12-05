<?php

namespace App\Services\Import;

use App\Models\Transaksi\Penjualan;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class PenjualanImportService
{
    public function handle(string $filePath)
    {
        // 1. MAX POWER RESOURCE
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();
            $rows = $reader->getRows();
            
            $stats = [
                'total_rows' => 0,
                'imported' => 0,
                'skipped_empty' => 0,
                'skipped_error' => 0,
            ];

            DB::beginTransaction();

            foreach ($rows as $index => $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values((array)$rawRow);
                    
                    // --- HELPER AKSES DATA ---
                    $val = function ($i) use ($row) {
                        return $this->cleanStr($row, $i);
                    };
                    
                    $cabangRaw = $val(0); 
                    $transNoRaw = $val(1); 
                    $kodeItem = $val(8); 

                    // 1. Skip Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0 || strcasecmp($transNoRaw, 'Trans No') === 0) {
                        continue; 
                    }

                    // 2. Skip jika Kunci Utama Kosong
                    if ($transNoRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // 3. Validasi Tanggal Wajib (Kolom D / Index 3)
                    $tglPenjualan = $this->parseDateStrict($row, 3);
                    
                    if (is_null($tglPenjualan)) {
                        // Log nilai asli untuk debugging
                        $rawVal = $row[3] ?? 'NULL';
                        if (is_array($rawVal)) $rawVal = json_encode($rawVal);
                        
                        throw new \Exception("Tanggal Penjualan (Kolom D) Invalid. Nilai Asli: [{$rawVal}]");
                    }

                    // 4. SIMPAN DATA
                    Penjualan::updateOrCreate(
                        [
                            'cabang'    => $cabangRaw ?: null,
                            'trans_no'  => $transNoRaw,
                            'kode_item' => $kodeItem
                        ],
                        [
                            'status'                => $val(2),
                            'tgl_penjualan'         => $tglPenjualan, 
                            
                            // Bersihkan kutip di Period & Jatuh Tempo
                            'period'                => $val(4), 
                            'jatuh_tempo'           => $this->parseDateLoose($row, 5), // Boleh null
                            
                            'kode_pelanggan'        => $val(6),
                            'nama_pelanggan'        => $val(7),
                            'sku'                   => $val(9),
                            'no_batch'              => $val(10),
                            'ed'                    => $this->parseDateLoose($row, 11),
                            'nama_item'             => $val(12),
                            
                            // ANGKA (Pakai numSafe)
                            'qty'                   => $this->numSafe($row, 13),
                            'satuan_jual'           => $val(14),
                            'qty_i'                 => $this->numSafe($row, 15),
                            'satuan_i'              => $val(16),
                            'nilai'                 => $this->numSafe($row, 17),
                            'rata2'                 => $this->numSafe($row, 18),
                            'up_percent'            => $this->numSafe($row, 19),
                            'nilai_up'              => $this->numSafe($row, 20),
                            'nilai_jual_pembulatan' => $this->numSafe($row, 21),
                            
                            'd1'                    => $this->numSafe($row, 22),
                            'd2'                    => $this->numSafe($row, 23),
                            'diskon_1'              => $this->numSafe($row, 24),
                            'diskon_2'              => $this->numSafe($row, 25),
                            'diskon_bawah'          => $this->numSafe($row, 26),
                            'total_diskon'          => $this->numSafe($row, 27),
                            
                            'nilai_jual_net'        => $this->numSafe($row, 28),
                            'total_harga_jual'      => $this->numSafe($row, 29),
                            'ppn_head'              => $this->numSafe($row, 30),
                            'total_grand'           => $this->numSafe($row, 31),
                            'ppn_value'             => $this->numSafe($row, 32),
                            'total_min_ppn'         => $this->numSafe($row, 33),
                            'margin'                => $this->numSafe($row, 34),
                            
                            'pembayaran'            => $val(35),
                            'cash_bank'             => $val(36),
                            'kode_sales'            => $val(37),
                            'sales_name'            => $val(38),
                            'supplier'              => $val(39),
                            'status_pay'            => $val(40),
                            'trx_id'                => $val(41),
                            
                            'year'                  => $this->numSafe($row, 42),
                            'month'                 => $this->numSafe($row, 43),
                            'last_suppliers'        => $val(44),
                            'mother_sku'            => $val(45),
                            'divisi'                => $val(46),
                            'program'               => $val(47),
                            'outlet_code_sales_name'=> $val(48),
                            'city_code_outlet_program' => $val(49),
                            'sales_name_outlet_code' => $val(50),
                        ]
                    );
                    
                    $stats['imported']++;

                    if ($stats['total_rows'] % 500 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    // LOG ERROR DETIL: Agar kita tahu kenapa gagal
                    Log::error("Import Penjualan Baris {$stats['total_rows']} Gagal. Pesan: " . $e->getMessage());
                    continue; 
                }
            }

            DB::commit();
            return $stats;

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Service Import Fatal: ' . $e->getMessage());
            throw $e;
        }
    }
    
    // --- HELPER ---

    private function cleanStr($row, $index)
    {
        if (!isset($row[$index])) return '';
        $val = $row[$index];
        
        if (is_object($val) || is_array($val)) {
            // Jika objek tanggal dari Excel
            if ($val instanceof \DateTimeInterface) {
                return $val->format('Y-m-d');
            }
            return json_encode($val);
        }

        // Hapus kutip tunggal, ganda, dan backtick
        return trim(str_replace(["'", '"', "`"], "", (string)$val));
    }

    private function parseDateStrict($row, $index)
    {
        $val = $this->cleanStr($row, $index); // Sudah bersih dari kutip
        if ($val === '' || $val === '-') return null;

        try {
            // Cek format angka Excel (cth: 45201)
            if (is_numeric($val)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d');
            }
            // Cek format Y-m-d (paling umum)
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
                return $val;
            }
            // Fallback parsing carbon
            return Carbon::parse($val)->format('Y-m-d');
        } catch (Throwable $e) {
            return null;
        }
    }

    private function parseDateLoose($row, $index)
    {
        return $this->parseDateStrict($row, $index); // Sama logicnya, tapi di main loop boleh null
    }

    private function numSafe($row, $index)
    {
        $val = $this->cleanStr($row, $index); // Bersih dari kutip
        
        if ($val === '' || $val === '-') return '0';
        
        // Handle kurung (100) -> -100.00
        if (str_starts_with($val, '(') && str_ends_with($val, ')')) {
            $val = '-' . trim($val, '()');
        }

        // Normalisasi angka (hapus titik ribuan, ganti koma desimal jadi titik)
        // Cth: 1.000,00 -> 1000.00
        $clean = preg_replace('/[^0-9.,-]/', '', $val);
        
        // Deteksi ribuan/desimal
        if (strpos($clean, ',') !== false && strpos($clean, '.') === false) {
             // Indo format (1000,00) -> 1000.00
             $clean = str_replace(',', '.', $clean);
        } elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
             // Mixed (1.000,00) -> 1000.00
             $clean = str_replace('.', '', $clean);
             $clean = str_replace(',', '.', $clean);
        }

        return $clean;
    }
}