<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }
}