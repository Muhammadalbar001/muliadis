<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
    //     $table = 'penjualans';
        
    //     // Cari semua index unik di tabel penjualans
    //     $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Non_unique = 0 AND Key_name != 'PRIMARY'");

    //     Schema::table($table, function (Blueprint $table) use ($indexes) {
    //         foreach ($indexes as $index) {
    //             // Hapus semua aturan unik (kecuali Primary Key)
    //             // Ini memastikan tidak ada lagi penolakan data kembar
    //             try {
    //                 $table->dropUnique($index->Key_name);
    //             } catch (\Exception $e) {
    //                 // Abaikan jika gagal
    //             }
    //         }
    //     });
    // }

    public function down() {}
};