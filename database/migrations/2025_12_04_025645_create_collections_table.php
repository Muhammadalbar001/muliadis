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

            $table->string('cabang')->nullable()->index();
            $table->string('receive_no')->nullable()->index();
            $table->string('status')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('penagih')->nullable();
            $table->string('invoice_no')->nullable()->index();
            $table->string('code_customer')->nullable();
            $table->string('outlet_name')->nullable();
            $table->string('sales_name')->nullable();
            $table->string('receive_amount')->default('0');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collections');
    }
};