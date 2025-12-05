<?php

namespace App\Services\Import;

use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;

class PenjualanImportService
{
    public function handle(string $filePath)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        DB::disableQueryLog();

        try {
            $reader = SimpleExcelReader::create($filePath)->noHeaderRow();

            $stats = [
                'total_rows'     => 0,
                'imported'       => 0,
                'skipped_empty'  => 0,
                'skipped_error'  => 0,
            ];

            $batchSize = 2000;
            $batchData = [];
            $now       = date('Y-m-d H:i:s');

            foreach ($reader->getRows() as $index => $rawRow) {

                try {
                    $stats['total_rows']++;

                    $row = array_values((array)$rawRow);

                    // Helper cepat
                    $val = fn($i) => $this->cleanStr($row, $i);

                    $cabangRaw  = $val(0);
                    $transNoRaw = $val(1);
                    $kodeItem   = $val(8);

                    if (strcasecmp($cabangRaw, 'cabang') === 0) continue;

                    if ($transNoRaw === '' || $kodeItem === '') {
                        $stats['skipped_empty']++;
                        continue;
                    }

                    $tglPenjualan = $this->parseDateFast($row, 3);
                    $jatuhTempo   = $this->parseDateFast($row, 5);
                    $ed           = $this->parseDateFast($row, 11);

                    // BUILD DATA ROW
                    $batchData[] = [
                        'cabang'    => $cabangRaw ?: null,
                        'trans_no'  => $transNoRaw,
                        'kode_item' => $kodeItem,

                        'status'            => $val(2),
                        'tgl_penjualan'     => $tglPenjualan,
                        'period'            => $val(4),
                        'jatuh_tempo'       => $jatuhTempo,
                        'kode_pelanggan'    => $val(6),
                        'nama_pelanggan'    => $val(7),
                        'sku'               => $val(9),
                        'no_batch'          => $val(10),
                        'ed'                => $ed,
                        'nama_item'         => $val(12),

                        'qty'               => $this->numSafeFast($row, 13),
                        'satuan_jual'       => $val(14),
                        'qty_i'             => $this->numSafeFast($row, 15),
                        'satuan_i'          => $val(16),
                        'nilai'             => $this->numSafeFast($row, 17),
                        'rata2'             => $this->numSafeFast($row, 18),
                        'up_percent'        => $this->numSafeFast($row, 19),
                        'nilai_up'          => $this->numSafeFast($row, 20),
                        'nilai_jual_pembulatan' => $this->numSafeFast($row, 21),

                        'd1'                => $this->numSafeFast($row, 22),
                        'd2'                => $this->numSafeFast($row, 23),
                        'diskon_1'          => $this->numSafeFast($row, 24),
                        'diskon_2'          => $this->numSafeFast($row, 25),
                        'diskon_bawah'      => $this->numSafeFast($row, 26),
                        'total_diskon'      => $this->numSafeFast($row, 27),

                        'nilai_jual_net'    => $this->numSafeFast($row, 28),
                        'total_harga_jual'  => $this->numSafeFast($row, 29),
                        'ppn_head'          => $this->numSafeFast($row, 30),
                        'total_grand'       => $this->numSafeFast($row, 31),
                        'ppn_value'         => $this->numSafeFast($row, 32),
                        'total_min_ppn'     => $this->numSafeFast($row, 33),
                        'margin'            => $this->numSafeFast($row, 34),

                        'pembayaran'        => $val(35),
                        'cash_bank'         => $val(36),
                        'kode_sales'        => $val(37),
                        'sales_name'        => $val(38),
                        'supplier'          => $val(39),
                        'status_pay'        => $val(40),
                        'trx_id'            => $val(41),
                        'year'              => $this->numSafeFast($row, 42),
                        'month'             => $this->numSafeFast($row, 43),

                        'last_suppliers'    => $val(44),
                        'mother_sku'        => $val(45),
                        'divisi'            => $val(46),
                        'program'           => $val(47),
                        'outlet_code_sales_name' => $val(48),
                        'city_code_outlet_program' => $val(49),
                        'sales_name_outlet_code'   => $val(50),

                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    // EXECUTE BATCH
                    if (count($batchData) >= $batchSize) {
                        $this->processBatch($batchData);
                        $stats['imported'] += count($batchData);
                        $batchData = [];
                    }

                } catch (Throwable $e) {
                    $stats['skipped_error']++;

                    if ($stats['skipped_error'] <= 10) {
                        Log::error("Skip row {$index}: ".$e->getMessage());
                    }
                    continue;
                }
            }

            // Last batch
            if (count($batchData) > 0) {
                $this->processBatch($batchData);
                $stats['imported'] += count($batchData);
            }

            return $stats;

        } catch (Throwable $e) {
            Log::error("Fatal Import Error: ".$e->getMessage());
            throw $e;
        }
    }

    /**
     * FAST UPSERT using Query Builder
     */
    private function processBatch(array $data)
    {
        DB::table('penjualans')->upsert(
            $data,
            ['cabang', 'trans_no', 'kode_item'],
            [
                'status', 'tgl_penjualan', 'period', 'jatuh_tempo',
                'kode_pelanggan', 'nama_pelanggan', 'sku', 'no_batch', 'ed', 'nama_item',
                'qty', 'satuan_jual', 'qty_i', 'satuan_i',
                'nilai', 'rata2', 'up_percent', 'nilai_up', 'nilai_jual_pembulatan',
                'd1', 'd2', 'diskon_1', 'diskon_2', 'diskon_bawah', 'total_diskon',
                'nilai_jual_net', 'total_harga_jual', 'ppn_head', 'total_grand', 'ppn_value',
                'total_min_ppn', 'margin',
                'pembayaran', 'cash_bank', 'kode_sales', 'sales_name', 'supplier',
                'status_pay', 'trx_id',
                'year', 'month', 'last_suppliers', 'mother_sku', 'divisi', 'program',
                'outlet_code_sales_name', 'city_code_outlet_program', 'sales_name_outlet_code',
                'updated_at'
            ]
        );
    }

    // -------------------------------------
    // FAST HELPERS
    // -------------------------------------

    private function cleanStr($row, $index)
    {
        if (!isset($row[$index])) return '';
        $v = $row[$index];

        if (is_scalar($v)) return trim(str_replace("'", "", (string)$v));

        if ($v instanceof \DateTimeInterface) return $v->format('Y-m-d');

        return json_encode($v);
    }

    private function parseDateFast($row, $index)
    {
        $v = $this->cleanStr($row, $index);

        if ($v === '' || $v === '-' || $v === 'Blank') return null;

        try {
            if (is_numeric($v)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)->format('Y-m-d');
            }

            $ts = strtotime($v);
            return $ts ? date('Y-m-d', $ts) : null;

        } catch (Throwable $e) {
            return null;
        }
    }

    private function numSafeFast($row, $index)
    {
        $v = $this->cleanStr($row, $index);

        if ($v === '' || $v === '-' || $v === '0') return 0;

        // If text has commas / dots mixed
        $clean = preg_replace('/[^0-9\.\-]/', '', str_replace(',', '.', $v));
        return $clean === '' ? 0 : $clean;
    }
}