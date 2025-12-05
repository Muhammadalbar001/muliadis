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
        // Setup Server
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
                    
                    // Helper Lokal
                    $val = function ($i) use ($row) {
                        return $this->cleanStr($row, $i);
                    };

                    // Ambil Kunci Utama
                    $cabangRaw  = $val(0); // Cabang
                    $noReturRaw = $val(1); // No Retur
                    $kodeItem   = $val(7); // Kode Item (PENTING: Index ke-7 sesuai Excel)

                    // 1. Skip Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue; 

                    // 2. Skip jika Kunci Kosong
                    if ($noReturRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // 3. Validasi Tanggal
                    // Kolom 'Retur' ada di Index 3
                    $tglRetur = $this->parseDateLoose($row, 3); 

                    // 4. SIMPAN DATA
                    Retur::updateOrCreate(
                        [
                            'cabang'    => $cabangRaw ?: null,
                            'no_retur'  => $noReturRaw,
                            'kode_item' => $kodeItem
                        ],
                        [
                            // Mapping 0 - 38 Sesuai Excel rekap returF.xlsx
                            'status'                => $val(2),
                            'tgl_retur'             => $tglRetur,
                            'no_inv'                => $val(4),
                            'kode_pelanggan'        => $val(5), // Kode
                            'nama_pelanggan'        => $val(6),
                            // Kode Item (7) dipakai di kunci
                            'nama_item'             => $val(8),
                            
                            // QTY & NILAI
                            'qty'                   => $this->numSafe($row, 9),
                            'satuan_retur'          => $val(10),
                            'nilai'                 => $this->numSafe($row, 11),
                            'rata2'                 => $this->numSafe($row, 12),
                            'up_percent'            => $this->numSafe($row, 13), // Up (%)
                            'nilai_up'              => $this->numSafe($row, 14),
                            'nilai_retur_pembulatan'=> $this->numSafe($row, 15), // Nilai Retur + Pembulatan
                            
                            // DISKON
                            'd1'                    => $this->numSafe($row, 16),
                            'd2'                    => $this->numSafe($row, 17),
                            'diskon_1'              => $this->numSafe($row, 18),
                            'diskon_2'              => $this->numSafe($row, 19),
                            'diskon_bawah'          => $this->numSafe($row, 20),
                            'total_diskon'          => $this->numSafe($row, 21), // T. Diskon
                            
                            // TOTAL
                            'nilai_retur_net'       => $this->numSafe($row, 22), // Nilai Retur - T. Diskon
                            'total_harga_retur'     => $this->numSafe($row, 23),
                            'ppn_head'              => $this->numSafe($row, 24),
                            'total_grand'           => $this->numSafe($row, 25), // TOTAL
                            'ppn_value'             => $this->numSafe($row, 26),
                            'total_min_ppn'         => $this->numSafe($row, 27), // TOTAL-PPN
                            'margin'                => $this->numSafe($row, 28),
                            
                            // META
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
    
    // --- HELPER SAKTI (Sama dengan Penjualan) ---

    private function cleanStr($row, $index)
    {
        if (!isset($row[$index])) return '';
        $val = $row[$index];
        
        if (is_object($val) || is_array($val)) {
            if ($val instanceof \DateTimeInterface) return $val->format('Y-m-d');
            return json_encode($val);
        }
        return trim(str_replace("'", "", (string)$val));
    }

    private function parseDateLoose($row, $index)
    {
        $val = $this->cleanStr($row, $index);
        if ($val === '' || $val === '-' || $val === 'Blank') return null;

        try {
            if (is_numeric($val)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d');
            }
            $val = $this->translateDateIndo($val);
            return Carbon::parse($val)->format('Y-m-d');
        } catch (Throwable $e) {
            return null;
        }
    }

    private function translateDateIndo($dateStr)
    {
        $map = [
            'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
            'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
            'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
            'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December',
            'Agust' => 'August', 'Okt' => 'October', 'Nop' => 'November', 'Des' => 'December'
        ];
        return strtr($dateStr, $map);
    }

    private function numSafe($row, $index): string
    {
        $val = $this->cleanStr($row, $index);
        
        if ($val === '' || $val === '0' || $val === '-') return '0';
        
        // Handle minus dan kurung
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