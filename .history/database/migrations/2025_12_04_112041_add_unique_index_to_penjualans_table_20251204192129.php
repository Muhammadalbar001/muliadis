<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Membuat kombinasi 3 kolom ini menjadi kunci unik
            // Ini PENTING agar fitur 'upsert' massal bisa bekerja
            $table->unique(['cabang', 'trans_no', 'kode_item'], 'penjualan_unique_index');
        });
    }

    public function down()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropUnique('penjualan_unique_index');
        });
    }
};