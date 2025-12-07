<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // 1. Tambah Kolom Cabang
            $table->string('cabang')->nullable()->after('id')->index();

            // 2. Update Aturan Unik
            // Hapus aturan "Nama Supplier harus unik sendiri"
            // Ganti jadi "Kombinasi Cabang + Nama Supplier harus unik"
            try {
                $table->dropUnique(['supplier_name']); // Hapus index lama (nama default laravel)
            } catch (\Exception $e) {
                // Abaikan jika tidak ketemu
            }
            
            // Buat aturan baru: Boleh nama sama asalkan beda cabang
            $table->unique(['cabang', 'supplier_name'], 'suppliers_cabang_name_unique');
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique('suppliers_cabang_name_unique');
            $table->dropColumn('cabang');
            $table->unique('supplier_name'); // Kembalikan aturan lama
        });
    }
};