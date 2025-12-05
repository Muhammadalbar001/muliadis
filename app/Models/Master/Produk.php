<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'produks';

    // MEMBUKA KUNCI: Mengizinkan semua kolom diisi (Mass Assignment) kecuali ID
    // Ini sangat penting karena tabel Anda memiliki 53 kolom.
    protected $guarded = ['id'];

    // CASTING: Mengubah tipe data saat diambil dari database
    protected $casts = [
        'expired_date' => 'date',
        'expired_info' => 'date',
    ];
}