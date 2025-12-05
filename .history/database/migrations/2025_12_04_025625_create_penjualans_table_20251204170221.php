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
            
            // KUNCI UTAMA (Composite Key)
            $table->string('cabang')->nullable()->index();
            $table->string('trans_no')->nullable()->index();
            $table->string('kode_item')->nullable()->index();

            // DATA TEXT
            $table->string('status')->nullable();
            $table->date('tgl_penjualan')->nullable(); // Kolom D
            $table->string('period')->nullable();       // Kolom E (String karena isinya '2025-01)
            $table->date('jatuh_tempo')->nullable();    // Kolom F
            
            $table->string('kode_pelanggan')->nullable()->index();
            $table->string('nama_pelanggan')->nullable();
            $table->string('sku')->nullable();
            $table->string('no_batch')->nullable();
            $table->date('ed')->nullable();
            $table->string('nama_item')->nullable();
            
            // ANGKA (Semua STRING agar persis Excel)
            $table->string('qty')->default('0');
            $table->string('satuan_jual')->nullable();
            $table->string('qty_i')->default('0');
            $table->string('satuan_i')->nullable();
            
            $table->string('nilai')->default('0');
            $table->string('rata2')->default('0');
            $table->string('up_percent')->default('0');
            $table->string('nilai_up')->default('0');
            $table->string('nilai_jual_pembulatan')->default('0');
            
            // DISKON
            $table->string('d1')->default('0');
            $table->string('d2')->default('0');
            $table->string('diskon_1')->default('0');
            $table->string('diskon_2')->default('0');
            $table->string('diskon_bawah')->default('0');
            $table->string('total_diskon')->default('0');
            
            // TOTAL
            $table->string('nilai_jual_net')->default('0');
            $table->string('total_harga_jual')->default('0');
            $table->string('ppn_head')->default('0');
            $table->string('total_grand')->default('0');
            $table->string('ppn_value')->default('0');
            $table->string('total_min_ppn')->default('0');
            $table->string('margin')->default('0');
            
            // SALES & META
            $table->string('pembayaran')->nullable();
            $table->string('cash_bank')->nullable();
            $table->string('kode_sales')->nullable();
            $table->string('sales_name')->nullable();
            $table->string('supplier')->nullable();
            $table->string('status_pay')->nullable();
            $table->string('trx_id')->nullable();
            
            $table->string('year')->nullable();
            $table->string('month')->nullable();
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