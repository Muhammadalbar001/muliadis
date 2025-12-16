<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('sales')->onDelete('cascade');
            $table->year('year');
            $table->tinyInteger('month'); // 1 - 12
            
            // Kita pakai string/double biar aman input koma/titik
            $table->decimal('target_ims', 20, 2)->default(0); 
            $table->integer('target_oa')->default(0);
            
            $table->timestamps();

            // Mencegah duplikat target untuk sales yang sama di bulan yang sama
            $table->unique(['sales_id', 'year', 'month']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_targets');
    }
};