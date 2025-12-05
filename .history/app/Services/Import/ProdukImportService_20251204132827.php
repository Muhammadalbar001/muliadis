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
        // 1. SETTING SUPER POWER
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

            // 2. MULAI TRANSAKSI
            DB::beginTransaction();

            foreach ($rows as $index => $rawRow) {
                try {
                    $stats['total_rows']++;
                    
                    // Pastikan $row adalah array index angka (0, 1, 2...)
                    $row = array_values($rawRow);

                    // --- CARA BARU: AKSES DATA VIA HELPER 'val()' ---
                    // Helper ini MENJAMIN return value adalah STRING.
                    // Tidak akan ada lagi error "Array to string conversion".
                    
                    $cabangRaw = $this->val($row, 0); // Ambil Kolom 0
                    $skuRaw    = $this->val($row, 2); // Ambil Kolom 2

                    // Skip Header "Cabang"
                    if (strtolower($cabangRaw) === 'cabang') {
                        continue; 
                    }

                    // Skip Empty SKU
                    if ($skuRaw === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // 3. SIMPAN DATA
                    Produk::updateOrCreate(
                        [
                            'sku'    => $skuRaw,
                            'cabang' => $cabangRaw ?: null // Jika kosong string, jadi null
                        ],
                        [
                            // Gunakan $this->val() untuk SEMUA TEKS
                            // Gunakan $this->num() untuk SEMUA ANGKA
                            // Gunakan $this->date() untuk SEMUA TANGGAL
                            
                            'ccode'             => $this->val($row, 1),
                            'kategori'          => $this->val($row, 3),
                            'name_item'         => $this->val($row, 4),
                            'expired_date'      => $this->date($row, 5),
                            'stok'              => $this->num($row, 6),
                            'oum'               => $this->val($row, 7),
                            'good'              => $this->num($row, 8),
                            'good_konversi'     => $this->val($row, 9),
                            'ktn'               => $this->num($row, 10),
                            'good_amount'       => $this->num($row, 11),
                            'avg_3m_in_oum'     => $this->num($row, 12),
                            'avg_3m_in_ktn'     => $this->num($row, 13),
                            'avg_3m_in_value'   => $this->num($row, 14),
                            'not_move_3m'       => $this->val($row, 15),
                            'bad'               => $this->num($row, 16),
                            'bad_konversi'      => $this->val($row, 17),
                            'bad_ktn'           => $this->num($row, 18),
                            'bad_amount'        => $this->num($row, 19),
                            'wrh1'              => $this->num($row, 20),
                            'wrh1_konversi'     => $this->val($row, 21),
                            'wrh1_amount'       => $this->num($row, 22),
                            'wrh2'              => $this->num($row, 23),
                            'wrh2_konversi'     => $this->val($row, 24),
                            'wrh2_amount'       => $this->num($row, 25),
                            'wrh3'              => $this->num($row, 26),
                            'wrh3_konversi'     => $this->val($row, 27),
                            'wrh3_amount'       => $this->num($row, 28),
                            'good_storage'      => $this->val($row, 29),
                            'sell_per_week'     => $this->num($row, 30),
                            'blank_field'       => $this->val($row, 31),
                            'empty_field'       => $this->val($row, 32),
                            'min'               => $this->num($row, 33),
                            're_qty'            => $this->num($row, 34),
                            'expired_info'      => $this->date($row, 35),
                            'buy'               => $this->num($row, 36),
                            'buy_disc'          => $this->num($row, 37),
                            'buy_in_ktn'        => $this->num($row, 38),
                            'avg'               => $this->num($row, 39),
                            'total'             => $this->num($row, 40),
                            'up'                => $this->num($row, 41),
                            'fix'               => $this->num($row, 42),
                            'ppn'               => $this->num($row, 43),
                            'fix_exc_ppn'       => $this->num($row, 44),
                            'margin'            => $this->num($row, 45),
                            'percent_margin'    => $this->num($row, 46),
                            'order_qty'         => $this->num($row, 47),
                            'supplier'          => $this->val($row, 48),
                            'mother_sku'        => $this->val($row, 49),
                            'last_supplier'     => $this->val($row, 50),
                            'divisi'            => $this->val($row, 51),
                            'unique_id'         => $this->val($row, 52),
                        ]
                    );
                    
                    $stats['imported']++;

                    // Commit per 200 baris
                    if ($stats['total_rows'] % 200 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                    }

                } catch (\Throwable $e) {
                    // Skip baris error, lanjut ke baris berikutnya
                    Log::error("Import Error Baris " . ($index+1) . ": " . $e->getMessage());
                    $stats['skipped_error']++;
                    continue; 
                }
            }

            DB::commit();
            return $stats;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Service Import Fatal: ' . $e->getMessage());
            throw $e;
        }
    }

    // --- HELPER SAKTI (ANTI ERROR) ---

    // 1. Ambil Nilai sebagai STRING (Aman dari Array)
    private function val(array $row, int $index): string
    {
        // Cek apakah index ada
        if (!isset($row[$index])) return '';

        $value = $row[$index];

        // Jika Array/Object, paksa jadi JSON string atau kosong
        if (is_array($value) || is_object($value)) {
            return ''; // Abaikan data array yang aneh
        }

        return trim((string) $value);
    }

    // 2. Ambil Nilai sebagai ANGKA (Float)
    private function num(array $row, int $index): float
    {
        $val = $this->val($row, $index); // Pakai helper val() dulu biar aman jadi string
        if ($val === '' || $val === '-') return 0;
        
        // Bersihkan format uang
        $clean = preg_replace('/[^0-9.,-]/', '', $val);
        
        if (strpos($clean, ',') !== false && strpos($clean, '.') === false) {
             $clean = str_replace(',', '.', $clean);
        } elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
             $clean = str_replace('.', '', $clean);
             $clean = str_replace(',', '.', $clean);
        }
        return (float) $clean;
    }

    // 3. Ambil Nilai sebagai TANGGAL (Y-m-d)
    private function date(array $row, int $index): ?string
    {
        $val = $this->val($row, $index); // Pakai helper val() dulu
        
        // Bersihkan kutip satu (')
        $val = ltrim($val, "'");

        if ($val === '' || $val === 'Blank' || $val === '-') return null;

        try {
            if (is_numeric($val)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d');
            }
            return Carbon::parse($val)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}