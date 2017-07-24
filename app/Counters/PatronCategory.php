<?php

namespace App\Counters;

use Illuminate\Database\Eloquent\Model;

class PatronCategory extends Model
{
    public $timestamps = false;
    
    public $fillable = [
        'name', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    /** 
     * Get the visit logs for the patron category.
     */
    public function visitsLogs()
    {
        return $this->hasMany('App\Counters\VisitsLog');
    }
}
