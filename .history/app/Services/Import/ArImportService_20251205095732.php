<?php

namespace App\Services\Import;

use App\Models\Keuangan\AccountReceivable;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class ArImportService
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
                'last_error_msg' => '',
            ];

            DB::beginTransaction();

            foreach ($rows as $index => $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values((array)$rawRow);
                    
                    // Helper Akses Data
                    $val = function ($i) use ($row) {
                        return $this->cleanStr($row, $i);
                    };
                    
                    $cabangRaw  = $val(0); 
                    $noJualRaw  = $val(1); // No Penjualan (Kunci Utama)

                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue; 

                    // Skip jika No Penjualan kosong
                    if ($noJualRaw === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // --- PARSING TANGGAL ---
                    $tglPenjualan = $this->parseDateSafe($row, 8); // Col I (Penjualan)
                    $tglAntar     = $this->parseDateSafe($row, 9); // Col J (Antar)
                    $jatuhTempo   = $this->parseDateSafe($row, 11); // Col L (Jatuh Tempo)

                    // 4. SIMPAN DATA
                    AccountReceivable::updateOrCreate(
                        [
                            'cabang'       => $cabangRaw ?: null,
                            'no_penjualan' => $noJualRaw,
                        ],
                        [
                            // Mapping 0 - 24 Sesuai Excel Rekap AR.xlsx
                            'pelanggan_code' => $val(2),
                            'pelanggan_name' => $val(3),
                            'sales_name'     => $val(4),
                            'info'           => $val(5),
                            
                            // NILAI
                            'total_nilai'    => $this->numSafe($row, 6),
                            'nilai'          => $this->numSafe($row, 7), // Sisa Piutang
                            
                            // TANGGAL
                            'tgl_penjualan'  => $tglPenjualan,
                            'tgl_antar'      => $tglAntar,
                            'status_antar'   => $val(10),
                            'jatuh_tempo'    => $jatuhTempo,
                            
                            // AGING 1
                            'current'        => $this->numSafe($row, 12),
                            'le_15_days'     => $this->numSafe($row, 13),
                            'bt_16_30_days'  => $this->numSafe($row, 14),
                            'gt_30_days'     => $this->numSafe($row, 15),
                            
                            // META
                            'status'         => $val(16),
                            'alamat'         => $val(17),
                            'phone'          => $val(18),
                            'umur_piutang'   => $this->numSafe($row, 19),
                            'unique_id'      => $val(20),
                            
                            // AGING 2
                            'lt_14_days'     => $this->numSafe($row, 21),
                            'bt_14_30_days'  => $this->numSafe($row, 22),
                            'up_30_days'     => $this->numSafe($row, 23),
                            'range_piutang'  => $val(24),
                        ]
                    );
                    
                    $stats['imported']++;

                    if ($stats['total_rows'] % 500 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;
                    $stats['last_error_msg'] = $e->getMessage();
                    Log::error("Skip AR Baris ke-{$index}: " . $e->getMessage());
                    continue; 
                }
            }

            DB::commit();
            return $stats;

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Import AR Fatal Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    // --- HELPER SAKTI ---

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

    private function parseDateSafe($row, $index)
    {
        $val = $this->cleanStr($row, $index);
        if ($val === '' || $val === '-' || $val === 'Blank' || $val === '0') return null;

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
            'Agust' => 'August', 'Okt' => 'October', 'Nop' => 'November', 'Des' => 'December',
            'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr', 'Mei' => 'May', 'Jun' => 'Jun',
            'Jul' => 'Jul', 'Agu' => 'Aug', 'Sep' => 'Sep', 'Okt' => 'Oct', 'Nov' => 'Nov', 'Des' => 'Dec'
        ];
        return strtr($dateStr, $map);
    }

    private function numSafe($row, $index): string
    {
        $val = $this->cleanStr($row, $index);
        
        if ($val === '' || $val === '0' || $val === '-') return '0';
        
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