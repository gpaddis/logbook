<?php

namespace App\Counters;

use Illuminate\Database\Eloquent\Model;

class RequestCategory extends Model
{
    public $timestamps = false;

    public $fillable = [
        'name', 'abbreviation', 'is_active'
    ];
}
