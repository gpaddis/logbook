<?php

namespace App\Counters;

use Illuminate\Database\Eloquent\Model;

class PatronCategory extends Model
{
    public $timestamps = false;
    
    public $fillable = [
        'name', 'is_active'
    ];

    /**
     * Scope a query to only include active patron categories.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
