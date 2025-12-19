<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'sales_code',
        'sales_name',
        'divisi',
        'status',
        'city',
        'target_ims', 
        'target_oa',  
    ];
}