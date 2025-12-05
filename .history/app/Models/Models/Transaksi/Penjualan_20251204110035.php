<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualans';

    // Mass Assignment Protection
    protected $guarded = ['id'];

    // Casting Tanggal & Angka Penting
    protected $casts = [
        'tgl_penjualan' => 'date',
        'jatuh_tempo' => 'date',
        'ed' => 'date', // Expired Date
        'total_grand' => 'decimal:2',
        'nilai' => 'decimal:2',
    ];
}