<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $table = 'collections';

    // Mass Assignment Protection
    protected $guarded = ['id'];

    // Casting Tanggal
    protected $casts = [
        'tanggal' => 'date',
    ];
    
    // Optional: Relasi ke AR (Piutang)
    public function accountReceivable()
    {
        // Menghubungkan Invoice No di Collection dengan No Penjualan di AR
        return $this->belongsTo(AccountReceivable::class, 'invoice_no', 'no_penjualan');
    }
}