<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Hapus index unik agar semua baris Excel bisa masuk (termasuk item double dalam 1 invoice)
            // Kita gunakan try-catch agar tidak error jika index sudah terhapus/beda nama
            try {
                $table->dropUnique(['cabang', 'trans_no', 'kode_item']); // Nama index default laravel
            } catch (\Exception $e) {
                // Coba nama index manual yang mungkin kita buat sebelumnya
                try {
                    $table->dropUnique('penjualans_cabang_trans_no_kode_item_unique');
                } catch (\Exception $ex) {}
            }
        });
    }

    public function down()
    {
        // Tidak perlu dikembalikan agar tetap fleksibel
    }
};