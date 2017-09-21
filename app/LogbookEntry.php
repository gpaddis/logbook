<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogbookEntry extends Model
{
    public $timestamps = false;

    public $fillable = ['patron_category_id', 'visited_at', 'recorded_at'];

    /**
     * Scope a query to only include logbook entries within the given start
     * and end date & time.
     *
     * @param  [type] $query
     * @param  string $start
     * @param  string $end
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithin($query, string $start, string $end)
    {
        return $query->where('visited_at', '>=', $start)
        ->where('visited_at', '<=', $end);
    }
}
