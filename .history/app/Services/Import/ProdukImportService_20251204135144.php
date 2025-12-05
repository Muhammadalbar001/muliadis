<?php

namespace App\Services\Import;

use App\Models\Master\Produk;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Throwable;

class ProdukImportService
{
    public function handle(string $filePath)
    {
        // 1. SETTING SUPER POWER (Unlimited)
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        try {
            // Baca file tanpa header agar index 0,1,2... pasti benar
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
                // Gunakan Throwable agar menangkap Error PHP (seperti Array to string)
                try {
                    $stats['total_rows']++;
                    
                    // Pastikan $row adalah array
                    $row = array_values((array)$rawRow);

                    // --- AMBIL DATA KUNCI DENGAN AMAN ---
                    $cabangRaw = $this->val($row, 0); // Kolom 0
                    $skuRaw    = $this->val($row, 2); // Kolom 2

                    // Skip Header "Cabang" (Case insensitive)
                    if (strcasecmp($cabangRaw, 'cabang') === 0) {
                        continue; 
                    }

                    // Skip jika SKU Kosong
                    if ($skuRaw === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    // 3. SIMPAN DATA (Update Or Create)
                    Produk::updateOrCreate(
                        [
                            'sku'    => $skuRaw,
                            'cabang' => $cabangRaw ?: null
                        ],
                        [
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

                    // Commit per 500 baris agar cepat & aman
                    if ($stats['total_rows'] % 500 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                    }

                } catch (Throwable $e) {
                    // JIKA ERROR: Catat dan LEWATI (Jangan berhenti)
                    $stats['skipped_error']++;
                    // Gunakan json_encode untuk index agar aman jika index array
                    Log::error("Skip Baris ke-" . json_encode($index) . ": " . $e->getMessage());
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

    /**
     * Helper Sakti: Mengambil nilai apa saja dan MEMAKSA jadi string.
     * Tidak akan pernah error "Array to string".
     */
    private function val($row, $index)
    {
        if (!isset($row[$index])) return '';
        
        $value = $row[$index];

        // Jika Array atau Object, ubah jadi JSON String (Misal: '["isi data"]')
        // Ini kuncinya agar tidak error "Array to string"
        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return trim((string)$value);
    }

    /**
     * Helper Angka: Memaksa jadi float, abaikan teks aneh.
     */
    private function num($row, $index)
    {
        $val = $this->val($row, $index); // Ambil sebagai string dulu
        
        if ($val === '' || $val === '-') return 0;
        
        // Hanya ambil angka, titik, koma, minus
        $clean = preg_replace('/[^0-9.,-]/', '', $val);
        
        // Deteksi format Desimal (Indo vs Inggris)
        if (strpos($clean, ',') !== false && strpos($clean, '.') === false) {
             // Cth: 100,50 -> 100.50
             $clean = str_replace(',', '.', $clean);
        } elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
             // Cth: 1.000,50 -> 1000.50
             $clean = str_replace('.', '', $clean);
             $clean = str_replace(',', '.', $clean);
        }
        
        return (float) $clean;
    }

    /**
     * Helper Tanggal: Memaksa jadi Y-m-d atau null.
     */
    private function date($row, $index)
    {
        $val = $this->val($row, $index);
        
        // Bersihkan kutip satu (') dan spasi
        $val = trim(str_replace("'", "", $val));

        if ($val === '' || $val === 'Blank' || $val === '-') return null;

        try {
            if (is_numeric($val)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d');
            }
            return Carbon::parse($val)->format('Y-m-d');
        } catch (Throwable $e) {
            return null; // Kalau gagal parse, biarkan null (jangan error)
        }
    }
}