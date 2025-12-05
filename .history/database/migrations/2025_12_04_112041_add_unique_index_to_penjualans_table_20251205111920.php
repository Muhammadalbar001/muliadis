<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Tambahkan Composite Unique Index
            // Pastikan nama kolom sesuai dengan database Anda
            $table->unique(['cabang', 'trans_no', 'kode_item'], 'penjualan_unique_idx');
        });
    }

    public function down()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropUnique('penjualan_unique_idx');
        });
    }
};