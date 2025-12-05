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
            
            // REVISI: Hapus Unique
            $table->string('no_penjualan')->index();
            
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
            
            $table->decimal('current', 15, 2)->default(0);
            $table->decimal('le_15_days', 15, 2)->default(0);
            $table->decimal('bt_16_30_days', 15, 2)->default(0);
            $table->decimal('gt_30_days', 15, 2)->default(0);
            
            $table->string('status')->nullable();
            $table->text('alamat')->nullable();
            $table->string('phone')->nullable();
            $table->integer('umur_piutang')->default(0);
            $table->string('unique_id')->nullable();
            
            $table->decimal('lt_14_days', 15, 2)->default(0);
            $table->decimal('bt_14_30_days', 15, 2)->default(0);
            $table->decimal('up_30_days', 15, 2)->default(0);
            $table->string('range_piutang')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_receivables');
    }
};