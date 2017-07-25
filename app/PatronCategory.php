<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatronCategory extends Model
{
    /**
     * Disable timestamps for the patron_categories table.
     * 
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name', 'abbreviation', 'is_active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
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
     * A patron category has many logbook entries.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logbookEntries()
    {
        return $this->hasMany('App\Logbook\Entry');
    }

    /**
     * Return the full path of the current patron category.
     * 
     * @return string
     */
    public function settingsPath()
    {
        return "/settings/patron-categories/{$this->id}";
    }
}
