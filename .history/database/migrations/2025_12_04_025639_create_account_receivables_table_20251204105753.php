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
            $table->string('cabang')->index();
            $table->string('no_penjualan')->index(); // Unique context
            $table->string('pelanggan_code')->index();
            $table->string('pelanggan_name')->nullable();
            $table->string('sales_name')->nullable();
            $table->text('info')->nullable();
            
            $table->decimal('total_nilai', 15, 2)->default(0);
            $table->decimal('nilai', 15, 2)->default(0);
            
            $table->date('tgl_penjualan');
            $table->date('tgl_antar')->nullable();
            $table->string('status_antar')->nullable();
            $table->date('jatuh_tempo')->nullable();
            
            // --- Aging Buckets ---
            $table->decimal('current', 15, 2)->default(0);
            $table->decimal('le_15_days', 15, 2)->default(0);   // <= 15
            $table->decimal('bt_16_30_days', 15, 2)->default(0); // 16-30
            $table->decimal('gt_30_days', 15, 2)->default(0);    // > 30
            
            $table->string('status')->nullable();
            $table->text('alamat')->nullable();
            $table->string('phone')->nullable();
            $table->integer('umur_piutang')->default(0);
            $table->string('unique_id')->nullable();
            
            // --- Additional Buckets ---
            $table->decimal('lt_14_days', 15, 2)->default(0);   // < 14 Days
            $table->decimal('bt_14_30_days', 15, 2)->default(0); // > 14 < 30 Days
            $table->decimal('up_30_days', 15, 2)->default(0);    // UP 30 Days
            $table->string('range_piutang')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_receivables');
    }
};