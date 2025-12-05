<?php

namespace App\Services\Import;

use App\Models\Keuangan\Collection;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class CollectionImportService
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
                    
                    // Helper Lokal
                    $val = function ($i) use ($row) {
                        return $this->cleanStr($row, $i);
                    };
                    
                    $cabangRaw  = $val(0); 
                    $receiveNoRaw = $val(1); 

                    // Skip Header
                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue; 

                    // Skip Empty
                    if ($receiveNoRaw === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // Parsing Tanggal
                    $tanggal = $this->parseDateLoose($row, 3);

                    // SIMPAN DATA
                    Collection::updateOrCreate(
                        [
                            'cabang'     => $cabangRaw ?: null,
                            'receive_no' => $receiveNoRaw,
                        ],
                        [
                            'status'          => $val(2),
                            'tanggal'         => $tanggal,
                            'penagih'         => $val(4),
                            'invoice_no'      => $val(5),
                            'code_customer'   => $val(6), // CODE
                            'outlet_name'     => $val(7), // OUTLET
                            'sales_name'      => $val(8), // SALES
                            'receive_amount'  => $this->numSafe($row, 9), // RECEIVE
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
                    Log::error("Skip Collection Baris ke-{$index}: " . $e->getMessage());
                    continue; 
                }
            }

            DB::commit();
            return $stats;

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Import Collection Fatal Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    // --- HELPER ---

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