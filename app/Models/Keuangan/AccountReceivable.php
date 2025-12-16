<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountReceivable extends Model
{
    use HasFactory;
    
    protected $table = 'account_receivables';
    protected $guarded = ['id'];
    
    protected $casts = [
        'tgl_penjualan' => 'date',
        'tgl_antar' => 'date',
        'jatuh_tempo' => 'date',
    ];
}