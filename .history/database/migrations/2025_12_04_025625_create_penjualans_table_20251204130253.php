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
            
            // --- KUNCI UTAMA (REVISI) ---
            $table->string('cabang')->index();
            // HAPUS ->unique(), ganti jadi ->index()
            $table->string('trans_no')->index(); 
            
            // Tambahkan Unique Constraint Ganda
            // Artinya: No Faktur boleh sama, ASALKAN Cabangnya beda.
            // (Opsional: Jika excel sering duplikat di 1 cabang, constraint ini bisa dimatikan)
            // $table->unique(['cabang', 'trans_no']); 

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
            
            // Angka-angka
            $table->decimal('qty', 12, 2)->default(0);
            $table->string('satuan_jual')->nullable();
            $table->decimal('qty_i', 12, 2)->default(0);
            $table->string('satuan_i')->nullable();
            
            $table->decimal('nilai', 15, 2)->default(0);
            $table->decimal('rata2', 15, 2)->default(0);
            $table->decimal('up_percent', 5, 2)->default(0);
            $table->decimal('nilai_up', 15, 2)->default(0);
            $table->decimal('nilai_jual_pembulatan', 15, 2)->default(0);
            
            $table->decimal('d1', 5, 2)->default(0);
            $table->decimal('d2', 5, 2)->default(0);
            $table->decimal('diskon_1', 15, 2)->default(0);
            $table->decimal('diskon_2', 15, 2)->default(0);
            $table->decimal('diskon_bawah', 15, 2)->default(0);
            $table->decimal('total_diskon', 15, 2)->default(0);
            
            $table->decimal('nilai_jual_net', 15, 2)->default(0);
            $table->decimal('total_harga_jual', 15, 2)->default(0);
            $table->decimal('ppn_head', 15, 2)->default(0);
            $table->decimal('total_grand', 15, 2)->default(0);
            $table->decimal('ppn_value', 15, 2)->default(0);
            $table->decimal('total_min_ppn', 15, 2)->default(0);
            $table->decimal('margin', 15, 2)->default(0);
            
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