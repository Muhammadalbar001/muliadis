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
            
            // Teks / Identitas
            $table->string('cabang')->index();
            $table->string('no_penjualan')->index();
            $table->string('pelanggan_code')->index();
            $table->string('pelanggan_name')->nullable();
            $table->string('sales_name')->nullable();
            $table->text('info')->nullable();
            
            // Nilai (Diubah jadi STRING)
            $table->string('total_nilai', 30)->default('0');
            $table->string('nilai', 30)->default('0');
            
            // Tanggal
            $table->date('tgl_penjualan');
            $table->date('tgl_antar')->nullable();
            $table->string('status_antar')->nullable();
            $table->date('jatuh_tempo')->nullable();
            
            // Aging Buckets (String)
            $table->string('current', 30)->default('0');
            $table->string('le_15_days', 30)->default('0');
            $table->string('bt_16_30_days', 30)->default('0');
            $table->string('gt_30_days', 30)->default('0');
            
            // Meta
            $table->string('status')->nullable();
            $table->text('alamat')->nullable();
            $table->string('phone')->nullable();
            $table->integer('umur_piutang')->default(0);
            $table->string('unique_id')->nullable();
            
            // Additional Buckets (String)
            $table->string('lt_14_days', 30)->default('0');
            $table->string('bt_14_30_days', 30)->default('0');
            $table->string('up_30_days', 30)->default('0');
            $table->string('range_piutang')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_receivables');
    }
};