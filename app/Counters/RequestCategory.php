<?php

namespace App\Counters;

use Illuminate\Database\Eloquent\Model;

class RequestCategory extends Model
{
    public $timestamps = false;

    public $fillable = [
        'category', 'is_active'
    ];
}
