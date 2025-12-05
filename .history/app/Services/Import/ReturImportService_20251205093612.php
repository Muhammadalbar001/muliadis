<?php

namespace App\Services\Import;

use App\Models\Transaksi\Retur;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class ReturImportService
{
    public function handle(string $filePath)
    {
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
                'last_error_msg' => '', // Variabel untuk menangkap pesan error asli
            ];

            DB::beginTransaction();

            foreach ($rows as $index => $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values((array)$rawRow);
                    
                    // --- HELPER AKSES DATA ---
                    $val = function ($i) use ($row) {
                        return $this->valClean($row, $i);
                    };
                    
                    // Kunci Utama
                    $cabangRaw  = $val(0); 
                    $noReturRaw = $val(1); 
                    $kodeItem   = $val(7); // PERHATIKAN: Index 7 sesuai Excel Anda

                    // Skip Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue; 

                    // Skip jika kunci utama kosong
                    if ($noReturRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // --- FORMAT TANGGAL YANG AMAN ---
                    // Jika gagal parse, kembalikan NULL (jangan error)
                    $tglRetur = $this->parseDateSafe($row, 3);

                    // --- SIMPAN DATA ---
                    Retur::updateOrCreate(
                        [
                            'cabang'    => $cabangRaw ?: null,
                            'no_retur'  => $noReturRaw,
                            'kode_item' => $kodeItem
                        ],
                        [
                            // Teks
                            'status'                => $val(2),
                            'tgl_retur'             => $tglRetur,
                            'no_inv'                => $val(4),
                            'kode_pelanggan'        => $val(5),
                            'nama_pelanggan'        => $val(6),
                            'nama_item'             => $val(8),
                            
                            // Angka (String)
                            'qty'                   => $this->numSafe($row, 9),
                            'satuan_retur'          => $val(10),
                            'nilai'                 => $this->numSafe($row, 11),
                            'rata2'                 => $this->numSafe($row, 12),
                            'up_percent'            => $this->numSafe($row, 13),
                            'nilai_up'              => $this->numSafe($row, 14),
                            'nilai_retur_pembulatan'=> $this->numSafe($row, 15),
                            
                            'd1'                    => $this->numSafe($row, 16),
                            'd2'                    => $this->numSafe($row, 17),
                            'diskon_1'              => $this->numSafe($row, 18),
                            'diskon_2'              => $this->numSafe($row, 19),
                            'diskon_bawah'          => $this->numSafe($row, 20),
                            'total_diskon'          => $this->numSafe($row, 21),
                            
                            'nilai_retur_net'       => $this->numSafe($row, 22),
                            'total_harga_retur'     => $this->numSafe($row, 23),
                            'ppn_head'              => $this->numSafe($row, 24),
                            'total_grand'           => $this->numSafe($row, 25),
                            'ppn_value'             => $this->numSafe($row, 26),
                            'total_min_ppn'         => $this->numSafe($row, 27),
                            'margin'                => $this->numSafe($row, 28),
                            
                            // Meta
                            'pembayaran'            => $val(29),
                            'sales_name'            => $val(30),
                            'supplier'              => $val(31),
                            'year'                  => $this->numSafe($row, 32),
                            'month'                 => $this->numSafe($row, 33),
                            'divisi'                => $val(34),
                            'program'               => $val(35),
                            'city_code'             => $val(36),
                            'mother_sku'            => $val(37),
                            'last_suppliers'        => $val(38),
                        ]
                    );
                    
                    $stats['imported']++;

                    if ($stats['total_rows'] % 200 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    // SIMPAN PESAN ERROR ASLI AGAR MUNCUL DI LAYAR
                    $stats['last_error_msg'] = $e->getMessage(); 
                    
                    Log::error("Skip Retur Baris ke-{$index}: " . $e->getMessage());
                    continue; 
                }
            }

            DB::commit();
            return $stats;

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Import Retur Fatal Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    // --- HELPER SAKTI ---

    private function valClean($row, $index)
    {
        if (!isset($row[$index])) return '';
        $val = $row[$index];
        
        if (is_object($val) || is_array($val)) {
            if ($val instanceof \DateTimeInterface) return $val->format('Y-m-d');
            return json_encode($val);
        }

        // Hapus kutip tunggal dan trim
        return trim(str_replace("'", "", (string)$val));
    }

    private function parseDateSafe($row, $index)
    {
        $val = $this->valClean($row, $index);
        
        // Hapus karakter yang bukan angka atau pemisah tanggal
        if (empty($val) || $val === '-' || $val === 'Blank' || $val === '0') return null;

        try {
            // Cek Excel Serial
            if (is_numeric($val)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d');
            }
            // Translate Indo -> Eng
            $val = $this->translateDateIndo($val);
            
            // Coba parse
            return Carbon::parse($val)->format('Y-m-d');
        } catch (Throwable $e) {
            // RETURN NULL JIKA GAGAL (Jangan Error)
            return null;
        }
    }

    private function translateDateIndo($dateStr)
    {
        // Peta bulan Indo ke Inggris
        $map = [
            'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
            'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
            'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
            'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December',
            'Agust' => 'August', 'Okt' => 'October', 'Nop' => 'November', 'Des' => 'December',
            'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr', 'Mei' => 'May', 'Jun' => 'Jun',
            'Jul' => 'Jul', 'Agu' => 'Aug', 'Sep' => 'Sep', 'Okt' => 'Oct', 'Nov' => 'Nov', 'Des' => 'Dec'
        ];
        return strtr($dateStr, $map);
    }

    private function numSafe($row, $index): string
    {
        $val = $this->valClean($row, $index);
        
        if ($val === '' || $val === '-') return '0';
        
        // Handle kurung negatif (500) -> -500.00
        if (str_starts_with($val, '(') && str_ends_with($val, ')')) {
            $number_part = trim($val, '()');
            $clean = preg_replace('/[^0-9.,-]/', '', $number_part);
            
            if (strpos($clean, ',') !== false && strpos($clean, '.') === false) {
                 $clean = str_replace(',', '.', $clean);
            } elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
                 $clean = str_replace('.', '', $clean);
                 $clean = str_replace(',', '.', $clean);
            }
            
            return '-' . number_format((float)$clean, 2, '.', '');
        }

        return $val;
    }
}