<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produks';
    
    // Baris ini PENTING agar semua kolom (53 kolom) bisa diisi sekaligus
    protected $guarded = ['id']; 
}