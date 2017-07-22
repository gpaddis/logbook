<?php

namespace App\Counters;

use Illuminate\Database\Eloquent\Model;

class PatronCategory extends Model
{
    public $timestamps = false;
    
    public $fillable = [
        'name', 'is_active'
    ];

    public static function active()
    {
        return PatronCategory::where('is_active', true);
    }
}
