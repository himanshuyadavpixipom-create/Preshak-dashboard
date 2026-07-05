<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Festival extends Model
{
    protected $fillable = [
        'name',
        'festival_date',
        'category',
    ];

    protected $casts = [
        'festival_date' => 'date',
    ];
}
