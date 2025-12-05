<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            // Kolom utama, diambil dari produk.supplier
            $table->string('supplier_name')->unique()->index(); 
            // Tempat untuk detail lain jika nanti diperlukan (e.g., alamat, kontak)
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};