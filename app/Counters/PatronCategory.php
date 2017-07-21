<?php

namespace App\Counters;

use Illuminate\Database\Eloquent\Model;

class PatronCategory extends Model
{
    public $timestamps = false;
    
    public $fillable = [
        'category', 'is_active'
    ];
}
