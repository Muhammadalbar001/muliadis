<?php

namespace App\Services\Import;

use App\Models\Master\Produk;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProdukImportService
{
    public function handle(string $filePath)
    {
        // 1. SETTING SUPER POWER (Unlimited Memory & Time)
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); // 0 = Unlimited time
        set_time_limit(0);

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();
            
            // Menggunakan LazyCollection agar hemat memori saat baca 15rb baris
            $rows = $reader->getRows();
            
            $stats = [
                'total_rows' => 0,
                'imported' => 0,
                'skipped_empty' => 0,
                'skipped_error' => 0, // Statistik baru untuk data error
            ];

            // 2. MULAI TRANSAKSI DATABASE
            DB::beginTransaction();

            foreach ($rows as $index => $rawRow) {
                try {
                    $stats['total_rows']++;
                    $row = array_values($rawRow);

                    // --- DEFINITIVE FIX: GLOBAL SANITIZATION ---
                    // Kita bersihkan satu baris penuh di awal.
                    // Apapun yang berbentuk Array akan dipaksa jadi String JSON
                    // agar tidak ada error "Array to string conversion" di bawah.
                    $row = array_map(function($cell) {
                        if (is_array($cell)) return json_encode($cell);
                        // Handle jika ada Object (misal DateTime object dari Excel reader)
                        if (is_object($cell)) {
                            if (method_exists($cell, '__toString')) return (string)$cell;
                            return json_encode($cell);
                        }
                        return $cell;
                    }, $row);

                    // --- LOGIC MAPPING (Sekarang aman karena $row pasti isinya string/null) ---
                    
                    // Ambil nilai kolom 0 (Cabang) dan 2 (SKU)
                    $rawCabang = $row[0] ?? null;
                    $rawSku    = $row[2] ?? null;

                    // Skip Header "Cabang"
                    if (is_string($rawCabang) && strtolower(trim($rawCabang)) === 'cabang') {
                        continue; 
                    }

                    // Skip Empty SKU
                    if (empty($rawSku) || trim((string)$rawSku) === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    $sku = trim((string)$rawSku);
                    $cabang = !empty($rawCabang) ? trim((string)$rawCabang) : null;

                    // 3. SIMPAN DATA
                    Produk::updateOrCreate(
                        [
                            'sku'    => $sku,
                            'cabang' => $cabang
                        ],
                        [
                            'ccode'             => $this->getString($row[1] ?? null),
                            'kategori'          => $this->getString($row[3] ?? null),
                            'name_item'         => $this->getString($row[4] ?? null),
                            'expired_date'      => $this->parseDate($row[5] ?? null),
                            'stok'              => $this->cleanNumber($row[6] ?? 0),
                            'oum'               => $this->getString($row[7] ?? null),
                            'good'              => $this->cleanNumber($row[8] ?? 0),
                            'good_konversi'     => $this->getString($row[9] ?? null),
                            'ktn'               => $this->cleanNumber($row[10] ?? 0),
                            'good_amount'       => $this->cleanNumber($row[11] ?? 0),
                            'avg_3m_in_oum'     => $this->cleanNumber($row[12] ?? 0),
                            'avg_3m_in_ktn'     => $this->cleanNumber($row[13] ?? 0),
                            'avg_3m_in_value'   => $this->cleanNumber($row[14] ?? 0),
                            'not_move_3m'       => $this->getString($row[15] ?? null),
                            'bad'               => $this->cleanNumber($row[16] ?? 0),
                            'bad_konversi'      => $this->getString($row[17] ?? null),
                            'bad_ktn'           => $this->cleanNumber($row[18] ?? 0),
                            'bad_amount'        => $this->cleanNumber($row[19] ?? 0),
                            'wrh1'              => $this->cleanNumber($row[20] ?? 0),
                            'wrh1_konversi'     => $this->getString($row[21] ?? null),
                            'wrh1_amount'       => $this->cleanNumber($row[22] ?? 0),
                            'wrh2'              => $this->cleanNumber($row[23] ?? 0),
                            'wrh2_konversi'     => $this->getString($row[24] ?? null),
                            'wrh2_amount'       => $this->cleanNumber($row[25] ?? 0),
                            'wrh3'              => $this->cleanNumber($row[26] ?? 0),
                            'wrh3_konversi'     => $this->getString($row[27] ?? null),
                            'wrh3_amount'       => $this->cleanNumber($row[28] ?? 0),
                            'good_storage'      => $this->getString($row[29] ?? null),
                            'sell_per_week'     => $this->cleanNumber($row[30] ?? 0),
                            'blank_field'       => $this->getString($row[31] ?? null),
                            'empty_field'       => $this->getString($row[32] ?? null),
                            'min'               => $this->cleanNumber($row[33] ?? 0),
                            're_qty'            => $this->cleanNumber($row[34] ?? 0),
                            'expired_info'      => $this->parseDate($row[35] ?? null),
                            'buy'               => $this->cleanNumber($row[36] ?? 0),
                            'buy_disc'          => $this->cleanNumber($row[37] ?? 0),
                            'buy_in_ktn'        => $this->cleanNumber($row[38] ?? 0),
                            'avg'               => $this->cleanNumber($row[39] ?? 0),
                            'total'             => $this->cleanNumber($row[40] ?? 0),
                            'up'                => $this->cleanNumber($row[41] ?? 0),
                            'fix'               => $this->cleanNumber($row[42] ?? 0),
                            'ppn'               => $this->cleanNumber($row[43] ?? 0),
                            'fix_exc_ppn'       => $this->cleanNumber($row[44] ?? 0),
                            'margin'            => $this->cleanNumber($row[45] ?? 0),
                            'percent_margin'    => $this->cleanNumber($row[46] ?? 0),
                            'order_qty'         => $this->cleanNumber($row[47] ?? 0),
                            'supplier'          => $this->getString($row[48] ?? null),
                            'mother_sku'        => $this->getString($row[49] ?? null),
                            'last_supplier'     => $this->getString($row[50] ?? null),
                            'divisi'            => $this->getString($row[51] ?? null),
                            'unique_id'         => $this->getString($row[52] ?? null),
                        ]
                    );
                    
                    $stats['imported']++;

                    // 4. COMMIT PER 200 BARIS
                    if ($stats['total_rows'] % 200 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                    }

                } catch (\Throwable $e) {
                    // CATCH ERROR PER BARIS
                    // Jika baris ini error, catat di log dan LANJUT ke baris berikutnya
                    // System TIDAK AKAN BERHENTI/CRASH
                    Log::error("Import Error pada Baris ke-" . ($index+1) . ": " . $e->getMessage());
                    $stats['skipped_error']++;
                    continue; 
                }
            }

            // Commit sisa data terakhir
            DB::commit();
            
            return $stats;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Service Import Fatal Error: ' . $e->getMessage());
            throw $e;
        }
    }

    // Helper: Ambil String Aman
    private function getString($value)
    {
        return (string) $value;
    }

    private function cleanNumber($value)
    {
        if (is_null($value) || $value === '' || $value === '-') return 0;
        
        $value = (string) $value;
        $clean = preg_replace('/[^0-9.,-]/', '', $value);
        
        if (strpos($clean, ',') !== false && strpos($clean, '.') === false) {
             $clean = str_replace(',', '.', $clean);
        } elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
             $clean = str_replace('.', '', $clean);
             $clean = str_replace(',', '.', $clean);
        }
        return (float) $clean;
    }

    private function parseDate($value)
    {
        if (empty($value) || $value === '-' || $value === 'Blank') return null;
        
        $value = ltrim((string)$value, "'");
        
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}