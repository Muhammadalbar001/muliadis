<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('produks', function (Blueprint $table) {
        // Gabungan cabang dan sku harus unik
        // Ini kuncinya agar upsert berjalan benar
        $table->unique(['cabang', 'sku'], 'produks_cabang_sku_unique');
    });
}

public function down()
{
    Schema::table('produks', function (Blueprint $table) {
        $table->dropUnique('produks_cabang_sku_unique');
    });
}
};