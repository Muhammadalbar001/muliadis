<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;
    
    protected $table = 'returs';
    protected $guarded = ['id'];
    
    // Hanya tanggal yang di-cast
    protected $casts = [
        'tgl_retur' => 'date',
    ];
}