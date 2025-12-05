<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            
            // Teks / Identitas
            $table->string('cabang')->index();
            $table->string('receive_no')->index(); 
            $table->string('status')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('penagih')->nullable();
            $table->string('invoice_no')->index();
            $table->string('code_customer')->nullable();
            $table->string('outlet_name')->nullable();
            $table->string('sales_name')->nullable();
            
            // Nilai (Diubah jadi STRING)
            $table->string('receive_amount', 30)->default('0');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collections');
    }
};