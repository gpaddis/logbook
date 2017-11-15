<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatronCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'abbreviation',
        'is_active',
        'is_primary',
        'notes'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
    ];

    /**
     * Scope a query to only include active patron categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include primary patron categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope a query to only include secondary patron categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeSecondary($query)
    {
        return $query->where('is_primary', false);
    }

    /**
     * A patron category has many logbook entries.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logbookEntries()
    {
        return $this->hasMany(LogbookEntry::class);
    }

    /**
     * Check if a patron category has visited within the given timeslot.
     *
     * @param  App\Timeslot  $timeslot
     *
     * @return boolean
     */
    public function hasVisited($timeslot = null)
    {
        return $this->logbookEntries()->withinTimeslot($timeslot)->exists();
    }

    /**
     * Return the number of visits within the current timeslot or 0 if there were none.
     *
     * @param  $timeslot
     *
     * @return int
     */
    public function currentVisits($timeslot = null)
    {
        if ($this->hasVisited($timeslot)) {
            return $this->logbookEntries()->withinTimeslot($timeslot)->first()->visits;
        }

        return 0;
    }

    /**
     * Return the full path of the current patron category.
     *
     * @return string
     */
    public function path()
    {
        return "/patron-categories/{$this->id}";
    }
}
