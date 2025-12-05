<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;

    protected $table = 'returs';

    // Mass Assignment Protection
    protected $guarded = ['id'];

    // Casting Tanggal
    protected $casts = [
        'tgl_retur' => 'date',
        'total_grand' => 'decimal:2',
    ];
    
    // Optional: Jika ingin relasi ke Penjualan di masa depan
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'no_inv', 'trans_no');
    }
}