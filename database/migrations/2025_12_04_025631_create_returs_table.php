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

            $table->string('cabang')->nullable()->index();
            $table->string('no_retur')->nullable()->index();
            $table->string('status')->nullable();
            $table->date('tgl_retur')->nullable();
            $table->string('no_inv')->nullable();
            $table->string('kode_pelanggan')->nullable();
            $table->string('nama_pelanggan')->nullable();

            $table->string('kode_item')->nullable();
            $table->string('nama_item')->nullable();
            $table->string('qty')->default('0');
            $table->string('satuan_retur')->nullable();
            $table->string('nilai')->default('0');
            $table->string('rata2')->default('0');
            $table->string('up_percent')->default('0');
            $table->string('nilai_up')->default('0');
            $table->string('nilai_retur_pembulatan')->default('0');

            $table->string('d1')->default('0');
            $table->string('d2')->default('0');
            $table->string('diskon_1')->default('0');
            $table->string('diskon_2')->default('0');
            $table->string('diskon_bawah')->default('0');
            $table->string('total_diskon')->default('0');

            $table->string('nilai_retur_net')->default('0');
            $table->string('total_harga_retur')->default('0');
            $table->string('ppn_head')->default('0');
            $table->string('total_grand')->default('0');
            $table->string('ppn_value')->default('0');
            $table->string('total_min_ppn')->default('0');
            $table->string('margin')->default('0');

            $table->string('pembayaran')->nullable();
            $table->string('sales_name')->nullable();
            $table->string('supplier')->nullable();
            $table->string('year')->nullable();
            $table->string('month')->nullable();
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