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
            // Kolom Cabang ditambahkan langsung di sini
            $table->string('cabang')->nullable()->index(); 
            $table->string('supplier_name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            // Kombinasi unik: Cabang + Nama (Boleh nama sama beda cabang)
            $table->unique(['cabang', 'supplier_name']);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};