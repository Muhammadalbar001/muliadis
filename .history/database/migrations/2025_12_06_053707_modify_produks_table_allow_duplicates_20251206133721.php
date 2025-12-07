<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('produks', function (Blueprint $table) {
            // 1. Hapus Index Unik agar data kembar bisa masuk
            // Nama index biasanya: produks_cabang_sku_unique (sesuai yang kita buat sebelumnya)
            // Kita pakai try-catch biar tidak error kalau index belum ada
            try {
                $table->dropUnique('produks_cabang_sku_unique');
            } catch (\Exception $e) {
                // Index mungkin belum ada atau namanya beda, abaikan
            }

            // 2. Tambah kolom penanda duplikat
            $table->boolean('is_duplicate')->default(false)->after('unique_id');
        });
    }

    public function down()
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn('is_duplicate');
            // $table->unique(['cabang', 'sku'], 'produks_cabang_sku_unique'); // Kembalikan jika perlu
        });
    }
};