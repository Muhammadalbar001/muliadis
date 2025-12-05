<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    
    protected $table = 'penjualans';
    protected $guarded = ['id'];
    
    // Hanya tanggal yang di-casting, sisanya string murni
    protected $casts = [
        'tgl_penjualan' => 'date',
        'jatuh_tempo' => 'date',
        'ed' => 'date',
    ];
}