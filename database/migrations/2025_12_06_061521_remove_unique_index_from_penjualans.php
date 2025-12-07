<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Nama tabel
        $tableName = 'penjualans';
        
        // Nama index default Laravel untuk unique(['cabang', 'trans_no', 'kode_item'])
        $indexName = 'penjualans_cabang_trans_no_kode_item_unique';

        // 1. Cek apakah Index benar-benar ada di database
        $indexExists = collect(DB::select("SHOW INDEX FROM {$tableName} WHERE Key_name = ?", [$indexName]))->count() > 0;

        // 2. Hanya drop jika index ada
        if ($indexExists) {
            Schema::table($tableName, function (Blueprint $table) use ($indexName) {
                $table->dropUnique($indexName);
            });
        }
    }

    public function down()
    {
        // Tidak perlu dikembalikan agar tetap fleksibel menerima duplikat
    }
};