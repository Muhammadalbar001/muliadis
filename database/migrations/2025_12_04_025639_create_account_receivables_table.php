<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('account_receivables', function (Blueprint $table) {
            $table->id();

            $table->string('cabang')->nullable()->index();
            $table->string('no_penjualan')->nullable()->index();
            $table->string('pelanggan_name')->nullable();
            $table->string('pelanggan_code')->nullable();
            $table->string('sales_name')->nullable();
            $table->string('info')->nullable();

            $table->string('total_nilai')->default('0');
            $table->string('nilai')->default('0'); // Sisa Piutang

            $table->date('tgl_penjualan')->nullable();
            $table->date('tgl_antar')->nullable();
            $table->string('status_antar')->nullable();
            $table->date('jatuh_tempo')->nullable();

            $table->string('current')->default('0');
            $table->string('le_15_days')->default('0');
            $table->string('bt_16_30_days')->default('0');
            $table->string('gt_30_days')->default('0');

            $table->string('status')->nullable();
            $table->text('alamat')->nullable();
            $table->string('phone')->nullable();
            $table->string('umur_piutang')->nullable();
            $table->string('unique_id')->nullable();

            $table->string('lt_14_days')->default('0');
            $table->string('bt_14_30_days')->default('0');
            $table->string('up_30_days')->default('0');
            $table->string('range_piutang')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_receivables');
    }
};