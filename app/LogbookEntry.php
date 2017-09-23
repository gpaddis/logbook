<?php

namespace App;

use Timeslot\Timeslot;
use Illuminate\Database\Eloquent\Model;

class LogbookEntry extends Model
{
    public $timestamps = false;

    public $fillable = ['patron_category_id', 'visited_at', 'recorded_live'];

    /**
     * Scope a query to only include logbook entries within the given start
     * and end date & time.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  string $start
     * @param  string $end
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithin($query, string $start, string $end)
    {
        return $query->where('visited_at', '>=', $start)
        ->where('visited_at', '<=', $end);
    }

    /**
     * A logbook entry belongs to a patron category.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patronCategory()
    {
        return $this->belongsTo('App\PatronCategory');
    }

    /**
     * Delete the most recent logbook entry for the given patron_category_id
     * within the timeslot provided.
     *
     * @param  Timeslot $timeslot
     * @param  int      $category
     * @return void
     */
    public static function deleteLatestRecord(Timeslot $timeslot, int $category)
    {
        if ($entry = static::within($timeslot->start(), $timeslot->end())
            ->where('patron_category_id', $category)
            ->orderBy('visited_at', 'desc')
            ->first()) {
            $entry->delete();
        }
    }
}
