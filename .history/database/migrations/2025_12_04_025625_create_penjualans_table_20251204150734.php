<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            
            // Teks / Identitas
            $table->string('cabang')->index();
            $table->string('trans_no')->index(); 
            $table->string('status')->nullable();
            $table->date('tgl_penjualan'); 
            $table->string('period')->nullable(); 
            $table->date('jatuh_tempo')->nullable();
            $table->string('kode_pelanggan')->index();
            $table->string('nama_pelanggan')->nullable();
            $table->string('kode_item')->index();
            $table->string('sku')->nullable();
            $table->string('no_batch')->nullable();
            $table->date('ed')->nullable();
            $table->string('nama_item')->nullable();
            
            // Kuantitas / Nilai (Diubah jadi STRING)
            $table->string('qty', 20)->default('0');
            $table->string('satuan_jual')->nullable();
            $table->string('qty_i', 20)->default('0');
            $table->string('satuan_i')->nullable();
            
            $table->string('nilai', 30)->default('0');
            $table->string('rata2', 30)->default('0');
            $table->string('up_percent', 10)->default('0');
            $table->string('nilai_up', 30)->default('0');
            $table->string('nilai_jual_pembulatan', 30)->default('0');
            
            // Diskon (String)
            $table->string('d1', 10)->default('0');
            $table->string('d2', 10)->default('0');
            $table->string('diskon_1', 30)->default('0');
            $table->string('diskon_2', 30)->default('0');
            $table->string('diskon_bawah', 30)->default('0');
            $table->string('total_diskon', 30)->default('0');
            
            $table->string('nilai_jual_net', 30)->default('0');
            $table->string('total_harga_jual', 30)->default('0');
            $table->string('ppn_head', 30)->default('0');
            $table->string('total_grand', 30)->default('0');
            $table->string('ppn_value', 30)->default('0');
            $table->string('total_min_ppn', 30)->default('0');
            $table->string('margin', 30)->default('0');
            
            // Sales & Meta
            $table->string('pembayaran')->nullable();
            $table->string('cash_bank')->nullable();
            $table->string('kode_sales')->nullable();
            $table->string('sales_name')->nullable();
            $table->string('supplier')->nullable();
            $table->string('status_pay')->nullable();
            $table->string('trx_id')->nullable();
            
            $table->year('year')->nullable();
            $table->integer('month')->nullable();
            $table->string('last_suppliers')->nullable();
            $table->string('mother_sku')->nullable();
            $table->string('divisi')->nullable();
            $table->string('program')->nullable();
            
            $table->string('outlet_code_sales_name')->nullable();
            $table->string('city_code_outlet_program')->nullable();
            $table->string('sales_name_outlet_code')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualans');
    }
};