<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            
            $table->string('sales_name')->index();
            $table->string('divisi')->nullable();
            $table->string('status')->default('Active');
            $table->string('target_ims')->default('0'); // String agar aman dari format excel
            $table->string('target_oa')->default('0');
            $table->string('city')->nullable(); // Cabang/Kota

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};