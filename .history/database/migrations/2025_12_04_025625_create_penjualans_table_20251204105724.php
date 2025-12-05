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
            
            // --- Header Transaksi ---
            $table->string('cabang')->index();
            $table->string('trans_no')->index(); // No Faktur (Bisa duplikat krn banyak item)
            $table->string('status')->nullable();
            $table->date('tgl_penjualan'); // Kolom 'Penjualan'
            $table->string('period')->nullable(); 
            $table->date('jatuh_tempo')->nullable();
            
            // --- Pelanggan & Item ---
            $table->string('kode_pelanggan')->index();
            $table->string('nama_pelanggan')->nullable();
            $table->string('kode_item')->index();
            $table->string('sku')->nullable();
            $table->string('no_batch')->nullable();
            $table->date('ed')->nullable(); // Expired Date
            $table->string('nama_item')->nullable();
            
            // --- Quantity ---
            $table->decimal('qty', 12, 2)->default(0);
            $table->string('satuan_jual')->nullable();
            $table->decimal('qty_i', 12, 2)->default(0);
            $table->string('satuan_i')->nullable();
            
            // --- Nilai Dasar ---
            $table->decimal('nilai', 15, 2)->default(0);
            $table->decimal('rata2', 15, 2)->default(0);
            $table->decimal('up_percent', 5, 2)->default(0);
            $table->decimal('nilai_up', 15, 2)->default(0);
            $table->decimal('nilai_jual_pembulatan', 15, 2)->default(0);
            
            // --- Diskon ---
            $table->decimal('d1', 5, 2)->default(0);
            $table->decimal('d2', 5, 2)->default(0);
            $table->decimal('diskon_1', 15, 2)->default(0);
            $table->decimal('diskon_2', 15, 2)->default(0);
            $table->decimal('diskon_bawah', 15, 2)->default(0);
            $table->decimal('total_diskon', 15, 2)->default(0);
            
            // --- Totals ---
            $table->decimal('nilai_jual_net', 15, 2)->default(0); // N. Jual - T. Diskon
            $table->decimal('total_harga_jual', 15, 2)->default(0);
            $table->decimal('ppn_head', 15, 2)->default(0);
            $table->decimal('total_grand', 15, 2)->default(0); // TOTAL
            $table->decimal('ppn_value', 15, 2)->default(0); // PPN
            $table->decimal('total_min_ppn', 15, 2)->default(0);
            $table->decimal('margin', 15, 2)->default(0);
            
            // --- Pembayaran & Sales ---
            $table->string('pembayaran')->nullable();
            $table->string('cash_bank')->nullable();
            $table->string('kode_sales')->nullable();
            $table->string('sales_name')->nullable();
            $table->string('supplier')->nullable();
            $table->string('status_pay')->nullable();
            $table->string('trx_id')->nullable(); // ID
            
            // --- Meta Data ---
            $table->year('year')->nullable();
            $table->integer('month')->nullable();
            $table->string('last_suppliers')->nullable();
            $table->string('mother_sku')->nullable();
            $table->string('divisi')->nullable();
            $table->string('program')->nullable();
            
            // --- Gabungan String ---
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