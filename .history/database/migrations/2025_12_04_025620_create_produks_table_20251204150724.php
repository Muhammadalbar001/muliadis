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
            
            // Kolom Teks / Identitas
            $table->string('cabang')->nullable()->index();
            $table->string('ccode')->nullable()->index();
            $table->string('sku')->index(); // String
            $table->string('kategori')->nullable();
            $table->string('name_item')->nullable();
            $table->date('expired_date')->nullable();
            
            // Kolom Nilai / Kuantitas (Diubah jadi STRING)
            $table->string('stok', 20)->default('0'); // String
            $table->string('oum')->nullable();
            
            // Good Stock (String)
            $table->string('good', 20)->default('0');
            $table->string('good_konversi')->nullable();
            $table->string('ktn', 20)->default('0'); 
            $table->string('good_amount', 30)->default('0');
            
            // AVG 3M (String)
            $table->string('avg_3m_in_oum', 20)->default('0');
            $table->string('avg_3m_in_ktn', 20)->default('0');
            $table->string('avg_3m_in_value', 30)->default('0');
            $table->string('not_move_3m')->nullable(); 
            
            // Bad Stock (String)
            $table->string('bad', 20)->default('0');
            $table->string('bad_konversi')->nullable();
            $table->string('bad_ktn', 20)->default('0'); 
            $table->string('bad_amount', 30)->default('0');
            
            // Warehouses (String)
            $table->string('wrh1', 20)->default('0');
            $table->string('wrh1_konversi')->nullable();
            $table->string('wrh1_amount', 30)->default('0');
            $table->string('wrh2', 20)->default('0');
            $table->string('wrh2_konversi')->nullable();
            $table->string('wrh2_amount', 30)->default('0');
            $table->string('wrh3', 20)->default('0');
            $table->string('wrh3_konversi')->nullable();
            $table->string('wrh3_amount', 30)->default('0');
            
            // Sales & Storage
            $table->string('good_storage')->nullable();
            $table->string('sell_per_week', 20)->default('0');
            $table->string('blank_field')->nullable(); 
            $table->string('empty_field')->nullable(); 
            $table->string('min', 20)->default('0');
            $table->string('re_qty', 20)->default('0');
            $table->date('expired_info')->nullable();
            
            // Buying Info (String)
            $table->string('buy', 30)->default('0');
            $table->string('buy_disc', 30)->default('0');
            $table->string('buy_in_ktn', 30)->default('0');
            $table->string('avg', 30)->default('0');
            $table->string('total', 30)->default('0');
            
            // Margin & Order (String)
            $table->string('up', 30)->default('0');
            $table->string('fix', 30)->default('0');
            $table->string('ppn', 30)->default('0');
            $table->string('fix_exc_ppn', 30)->default('0');
            $table->string('margin', 30)->default('0'); 
            $table->string('percent_margin', 30)->default('0');
            $table->string('order_no')->nullable(); 
            
            // Meta Data
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