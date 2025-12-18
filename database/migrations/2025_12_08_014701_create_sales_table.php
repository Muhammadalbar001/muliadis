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
        $table->string('sales_code')->nullable()->unique(); 
        $table->string('sales_name');
        $table->string('divisi')->nullable();
        $table->string('city')->nullable();
        $table->enum('status', ['Active', 'Inactive'])->default('Active');
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};