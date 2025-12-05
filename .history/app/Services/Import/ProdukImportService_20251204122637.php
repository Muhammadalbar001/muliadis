<?php

namespace App\Services\Import;

use App\Models\Master\Produk;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProdukImportService
{
    public function handle(string $filePath)
    {
        // 1. Setup Resource
        ini_set('memory_limit', '-1');
        set_time_limit(600);

        try {
            // 2. Baca File TANPA HEADER (Mode Index Angka)
            // Ini membuat $row menjadi array [0 => 'Data', 1 => 'Data', ...]
            $reader = SimpleExcelReader::create($filePath)
                ->noHeaderRow(); 

            $rows = $reader->getRows();
            $count = 0;

            foreach ($rows as $index => $row) {
                // 3. Skip Baris Judul (Header Row)
                // Cek jika kolom pertama (0) adalah tulisan "Cabang"
                if (isset($row[0]) && strtolower(trim($row[0])) === 'cabang') {
                    continue;
                }

                // 4. Skip Baris Kosong (Jika SKU di Index 2 kosong)
                if (empty($row[2])) {
                    continue;
                }

                // 5. MAPPING BERDASARKAN URUTAN KOLOM (INDEX 0 - 52)
                Produk::updateOrCreate(
                    ['sku' => trim($row[2])], // Index 2: SKU
                    [
                        // --- IDENTITAS ---
                        'cabang'            => $row[0] ?? null,  // Cabang
                        'ccode'             => $row[1] ?? null,  // CCODE
                        // SKU ada di index 2 (dipakai di kunci atas)
                        'kategori'          => $row[3] ?? null,  // KATEGORI
                        'name_item'         => $row[4] ?? null,  // NAME ITEM
                        'expired_date'      => $this->parseDate($row[5] ?? null), // EXPIRED
                        'stok'              => $this->cleanNumber($row[6] ?? 0),  // STOK
                        'oum'               => $row[7] ?? null,  // OUM
                        
                        // --- GOOD STOCK ---
                        'good'              => $this->cleanNumber($row[8] ?? 0),  // GOOD
                        'good_konversi'     => $row[9] ?? null, // GOOD KONVERSI
                        'ktn'               => $this->cleanNumber($row[10] ?? 0), // KTN
                        'good_amount'       => $this->cleanNumber($row[11] ?? 0), // GOOD AMOUNT
                        
                        // --- AVG 3M ---
                        'avg_3m_in_oum'     => $this->cleanNumber($row[12] ?? 0),
                        'avg_3m_in_ktn'     => $this->cleanNumber($row[13] ?? 0),
                        'avg_3m_in_value'   => $this->cleanNumber($row[14] ?? 0),
                        'not_move_3m'       => $row[15] ?? null,
                        
                        // --- BAD STOCK ---
                        'bad'               => $this->cleanNumber($row[16] ?? 0),
                        'bad_konversi'      => $row[17] ?? null,
                        'bad_ktn'           => $this->cleanNumber($row[18] ?? 0), // KTN (Bad)
                        'bad_amount'        => $this->cleanNumber($row[19] ?? 0),
                        
                        // --- WAREHOUSE 1 ---
                        'wrh1'              => $this->cleanNumber($row[20] ?? 0),
                        'wrh1_konversi'     => $row[21] ?? null,
                        'wrh1_amount'       => $this->cleanNumber($row[22] ?? 0),
                        
                        // --- WAREHOUSE 2 ---
                        'wrh2'              => $this->cleanNumber($row[23] ?? 0),
                        'wrh2_konversi'     => $row[24] ?? null,
                        'wrh2_amount'       => $this->cleanNumber($row[25] ?? 0),
                        
                        // --- WAREHOUSE 3 ---
                        'wrh3'              => $this->cleanNumber($row[26] ?? 0),
                        'wrh3_konversi'     => $row[27] ?? null,
                        'wrh3_amount'       => $this->cleanNumber($row[28] ?? 0),
                        
                        // --- STORAGE & SALES ---
                        'good_storage'      => $row[29] ?? null,
                        'sell_per_week'     => $this->cleanNumber($row[30] ?? 0),
                        'blank_field'       => $row[31] ?? null, // Blank
                        'empty_field'       => $row[32] ?? null, // EMPTY
                        'min'               => $this->cleanNumber($row[33] ?? 0),
                        're_qty'            => $this->cleanNumber($row[34] ?? 0),
                        'expired_info'      => $this->parseDate($row[35] ?? null), // EXPIRED (Info)
                        
                        // --- BUYING ---
                        'buy'               => $this->cleanNumber($row[36] ?? 0),
                        'buy_disc'          => $this->cleanNumber($row[37] ?? 0),
                        'buy_in_ktn'        => $this->cleanNumber($row[38] ?? 0),
                        'avg'               => $this->cleanNumber($row[39] ?? 0),
                        'total'             => $this->cleanNumber($row[40] ?? 0),
                        
                        // --- MARGIN & META ---
                        'up'                => $this->cleanNumber($row[41] ?? 0),
                        'fix'               => $this->cleanNumber($row[42] ?? 0),
                        'ppn'               => $this->cleanNumber($row[43] ?? 0),
                        'fix_exc_ppn'       => $this->cleanNumber($row[44] ?? 0),
                        'margin'            => $this->cleanNumber($row[45] ?? 0),
                        'percent_margin'    => $this->cleanNumber($row[46] ?? 0), // % MARGIN
                        'order_qty'         => $this->cleanNumber($row[47] ?? 0), // ORDER
                        'supplier'          => $row[48] ?? null,
                        'mother_sku'        => $row[49] ?? null,
                        'last_supplier'     => $row[50] ?? null,
                        'divisi'            => $row[51] ?? null,
                        'unique_id'         => $row[52] ?? null,
                    ]
                );
                $count++;
            }
            
            return $count;

        } catch (\Exception $e) {
            Log::error('Service Import Error: ' . $e->getMessage());
            throw $e;
        }
    }

    // Helper: Membersihkan Angka (Hapus "Rp", Titik ribuan, dll)
    private function cleanNumber($value)
    {
        if (is_null($value) || $value === '' || $value === '-') return 0;
        
        // Jika Excel membaca angka sebagai string (misal "1.500,00" format Indo)
        if (is_string($value)) {
            // Hapus semua karakter kecuali angka, titik, minus, dan koma
            $clean = preg_replace('/[^0-9.,-]/', '', $value);
            
            // Cek apakah pakai format Indonesia (koma sebagai desimal)
            // Jika ada koma tapi tidak ada titik, atau koma di posisi akhir
            if (strpos($clean, ',') !== false && strpos($clean, '.') === false) {
                 $clean = str_replace(',', '.', $clean);
            }
            // Jika campuran (misal 1.000,50)
            elseif (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
                 $clean = str_replace('.', '', $clean); // Hapus titik ribuan
                 $clean = str_replace(',', '.', $clean); // Ubah koma jadi titik desimal
            }
            
            return (float) $clean;
        }
        
        return (float) $value;
    }

    private function parseDate($value)
    {
        if (!$value || $value === '-' || $value === 'Blank') return null;
        try {
            // Coba parse format Excel numeric date (contoh: 45201)
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            // Coba parse format string biasa
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}