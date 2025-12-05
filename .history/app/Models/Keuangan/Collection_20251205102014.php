<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    
    protected $table = 'collections';
    protected $guarded = ['id'];
    
    protected $casts = [
        'tanggal' => 'date',
    ];
}