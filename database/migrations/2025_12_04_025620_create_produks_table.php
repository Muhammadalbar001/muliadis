<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            
            // IDENTITAS UTAMA
            $table->string('cabang')->nullable()->index(); // Index untuk filter cepat
            $table->string('ccode')->nullable();
            $table->string('sku')->nullable()->index();    // Index untuk pencarian
            $table->string('kategori')->nullable()->index();
            $table->string('name_item')->nullable();
            $table->date('expired_date')->nullable();

            // STOK & SATUAN
            $table->string('stok')->default('0');
            $table->string('oum')->nullable();

            // RINCIAN STOK (GOOD/BAD)
            $table->string('good')->default('0');
            $table->string('good_konversi')->nullable();
            $table->string('ktn')->default('0');
            $table->string('good_amount')->default('0');

            $table->string('avg_3m_in_oum')->default('0');
            $table->string('avg_3m_in_ktn')->default('0');
            $table->string('avg_3m_in_value')->default('0');
            $table->string('not_move_3m')->nullable();

            $table->string('bad')->default('0');
            $table->string('bad_konversi')->nullable();
            $table->string('bad_ktn')->default('0');
            $table->string('bad_amount')->default('0');

            // GUDANG (WAREHOUSE)
            $table->string('wrh1')->default('0');
            $table->string('wrh1_konversi')->nullable();
            $table->string('wrh1_amount')->default('0');
            $table->string('wrh2')->default('0');
            $table->string('wrh2_konversi')->nullable();
            $table->string('wrh2_amount')->default('0');
            $table->string('wrh3')->default('0');
            $table->string('wrh3_konversi')->nullable();
            $table->string('wrh3_amount')->default('0');

            // SALES INFO
            $table->string('good_storage')->nullable();
            $table->string('sell_per_week')->default('0');
            $table->string('blank_field')->nullable();
            $table->string('empty_field')->nullable();
            $table->string('min')->default('0');
            $table->string('re_qty')->default('0');
            $table->date('expired_info')->nullable();

            // HARGA BELI & MARGIN
            $table->string('buy')->default('0');
            $table->string('buy_disc')->default('0');
            $table->string('buy_in_ktn')->default('0');
            $table->string('avg')->default('0');
            $table->string('total')->default('0');

            $table->string('up')->default('0');
            $table->string('fix')->default('0');
            $table->string('ppn')->default('0');
            $table->string('fix_exc_ppn')->default('0');
            $table->string('margin')->default('0');
            $table->string('percent_margin')->default('0');
            $table->string('order_no')->nullable();

            // META DATA
            $table->string('supplier')->nullable()->index();
            $table->string('mother_sku')->nullable();
            $table->string('last_supplier')->nullable();
            $table->string('divisi')->nullable()->index();
            $table->string('unique_id')->nullable();
            
            // PENANDA DUPLIKAT (Agar kita tahu di tampilan)
            $table->boolean('is_duplicate')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produks');
    }
};  