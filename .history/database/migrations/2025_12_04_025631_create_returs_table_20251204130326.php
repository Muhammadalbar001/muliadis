<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('returs', function (Blueprint $table) {
            $table->id();
            $table->string('cabang')->index();
            
            // REVISI: Hapus Unique
            $table->string('no_retur')->index();
            
            $table->string('status')->nullable();
            $table->date('tgl_retur');
            $table->string('no_inv')->index(); 
            $table->string('kode_pelanggan')->nullable();
            $table->string('nama_pelanggan')->nullable();
            $table->string('kode_item')->nullable();
            $table->string('nama_item')->nullable();
            
            $table->decimal('qty', 12, 2)->default(0);
            $table->string('satuan_retur')->nullable();
            $table->decimal('nilai', 15, 2)->default(0);
            $table->decimal('rata2', 15, 2)->default(0);
            $table->decimal('up_percent', 5, 2)->default(0);
            $table->decimal('nilai_up', 15, 2)->default(0);
            $table->decimal('nilai_retur_pembulatan', 15, 2)->default(0);
            
            $table->decimal('d1', 5, 2)->default(0);
            $table->decimal('d2', 5, 2)->default(0);
            $table->decimal('diskon_1', 15, 2)->default(0);
            $table->decimal('diskon_2', 15, 2)->default(0);
            $table->decimal('diskon_bawah', 15, 2)->default(0);
            $table->decimal('total_diskon', 15, 2)->default(0);
            
            $table->decimal('nilai_retur_net', 15, 2)->default(0);
            $table->decimal('total_harga_retur', 15, 2)->default(0);
            $table->decimal('ppn_head', 15, 2)->default(0);
            $table->decimal('total_grand', 15, 2)->default(0);
            $table->decimal('ppn_value', 15, 2)->default(0);
            $table->decimal('total_min_ppn', 15, 2)->default(0);
            $table->decimal('margin', 15, 2)->default(0);
            
            $table->string('pembayaran')->nullable();
            $table->string('sales_name')->nullable();
            $table->string('supplier')->nullable();
            
            $table->year('year')->nullable();
            $table->integer('month')->nullable();
            $table->string('divisi')->nullable();
            $table->string('program')->nullable();
            $table->string('city_code')->nullable();
            $table->string('mother_sku')->nullable();
            $table->string('last_suppliers')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('returs');
    }
};