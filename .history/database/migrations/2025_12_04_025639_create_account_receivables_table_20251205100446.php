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
            
            // KUNCI UTAMA
            $table->string('cabang')->index()->nullable();
            $table->string('no_penjualan')->index()->nullable(); // No Invoice
            
            // IDENTITAS
            $table->string('pelanggan_code')->index()->nullable();
            $table->string('pelanggan_name')->nullable();
            $table->string('sales_name')->nullable();
            $table->text('info')->nullable();
            
            // NILAI (STRING)
            $table->string('total_nilai')->default('0');
            $table->string('nilai')->default('0'); // Sisa Piutang
            
            // TANGGAL (NULLABLE)
            $table->date('tgl_penjualan')->nullable();
            $table->date('tgl_antar')->nullable();
            $table->string('status_antar')->nullable();
            $table->date('jatuh_tempo')->nullable();
            
            // AGING BUCKETS (STRING)
            $table->string('current')->default('0');
            $table->string('le_15_days')->default('0');   // <= 15 Hari
            $table->string('bt_16_30_days')->default('0'); // 16-30 Hari
            $table->string('gt_30_days')->default('0');    // > 30 Hari
            
            // META
            $table->string('status')->nullable();
            $table->text('alamat')->nullable();
            $table->string('phone')->nullable();
            $table->string('umur_piutang')->default('0');
            $table->string('unique_id')->nullable();
            
            // ADDITIONAL BUCKETS
            $table->string('lt_14_days')->default('0');   // < 14 Days
            $table->string('bt_14_30_days')->default('0'); // > 14 < 30 Days
            $table->string('up_30_days')->default('0');    // UP 30 Days
            $table->string('range_piutang')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_receivables');
    }
};