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
            
            // --- IDENTITAS BARANG ---
            $table->string('cabang')->nullable()->index();
            $table->string('ccode')->nullable()->index();
            $table->string('sku')->index(); // Multi-Cabang
            $table->string('kategori')->nullable();
            $table->string('name_item')->nullable();
            $table->date('expired_date')->nullable();
            $table->decimal('stok', 12, 2)->default(0);
            $table->string('oum')->nullable();
            
            // --- GOOD STOCK ---
            $table->decimal('good', 12, 2)->default(0);
            $table->string('good_konversi')->nullable();
            $table->decimal('ktn', 12, 2)->default(0); 
            $table->decimal('good_amount', 15, 2)->default(0);
            
            // --- AVG 3M ---
            $table->decimal('avg_3m_in_oum', 12, 2)->default(0);
            $table->decimal('avg_3m_in_ktn', 12, 2)->default(0);
            $table->decimal('avg_3m_in_value', 15, 2)->default(0);
            $table->string('not_move_3m')->nullable(); 
            
            // --- BAD STOCK ---
            $table->decimal('bad', 12, 2)->default(0);
            $table->string('bad_konversi')->nullable();
            $table->decimal('bad_ktn', 12, 2)->default(0); 
            $table->decimal('bad_amount', 15, 2)->default(0);
            
            // --- WAREHOUSE ---
            $table->decimal('wrh1', 12, 2)->default(0);
            $table->string('wrh1_konversi')->nullable();
            $table->decimal('wrh1_amount', 15, 2)->default(0);
            
            $table->decimal('wrh2', 12, 2)->default(0);
            $table->string('wrh2_konversi')->nullable();
            $table->decimal('wrh2_amount', 15, 2)->default(0);
            
            $table->decimal('wrh3', 12, 2)->default(0);
            $table->string('wrh3_konversi')->nullable();
            $table->decimal('wrh3_amount', 15, 2)->default(0);
            
            // --- SALES & STORAGE ---
            $table->string('good_storage')->nullable();
            $table->decimal('sell_per_week', 12, 2)->default(0);
            $table->string('blank_field')->nullable(); 
            $table->string('empty_field')->nullable(); 
            $table->decimal('min', 12, 2)->default(0);
            $table->decimal('re_qty', 12, 2)->default(0);
            $table->date('expired_info')->nullable();
            
            // --- BUYING INFO ---
            $table->decimal('buy', 15, 2)->default(0);
            $table->decimal('buy_disc', 15, 2)->default(0);
            $table->decimal('buy_in_ktn', 15, 2)->default(0);
            $table->decimal('avg', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            
            // --- MARGIN & ORDER (REVISI) ---
            $table->decimal('up', 15, 2)->default(0);
            $table->decimal('fix', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('fix_exc_ppn', 15, 2)->default(0);
            // MARGIN dan %MARGIN harus menampung nilai negatif (tanpa unsigned)
            $table->decimal('margin', 15, 2)->default(0); 
            $table->decimal('percent_margin', 8, 2)->default(0);
            
            // ORDER: Diubah menjadi STRING (Varchar) karena berisi No PO
            $table->string('order_no')->nullable(); 
            
            // --- META DATA ---
            $table->string('supplier')->nullable();
            $table->string('mother_sku')->nullable();
            $table->string('last_supplier')->nullable();
            $table->string('divisi')->nullable();
            $table->string('unique_id')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produks');
    }
};