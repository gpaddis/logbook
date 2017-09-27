<?php

namespace App;

use Carbon\Carbon;
use Timeslot\Timeslot;
use Timeslot\TimeslotInterface;
use Illuminate\Database\Eloquent\Model;

class LogbookEntry extends Model
{
    /**
     * This model's table does not include timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The properties that can be mass assigned.
     *
     * @var array
     */
    public $fillable = ['patron_category_id', 'visited_at', 'recorded_live'];

    /**
     * Cast the field visited_at to a Carbon instance automativally.
     *
     * @var array
     */
    protected $dates = ['visited_at'];

    /**
     * Scope a query to only include logbook entries within the given start
     * and end date & time.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  string $start
     * @param  string $end
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithin($query, string $start, string $end)
    {
        return $query->where('visited_at', '>=', $start)
        ->where('visited_at', '<=', $end);
    }

    /**
     * Scope a query to only include logbook entries within the given timeslot.
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  Timeslot\TimeslotInterface           $timeslot
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithinTimeslot($query, TimeslotInterface $timeslot)
    {
        return $query->where('visited_at', '>=', $timeslot->start())
        ->where('visited_at', '<=', $timeslot->end());
    }

    /**
     * Get aggregate values for a custom time range.
     *
     * @param  Carbon $start
     * @param  Carbon $end
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getAggregatesWithin(Carbon $start, Carbon $end)
    {
        return static::selectRaw('MONTH(visited_at) as month, WEEK(visited_at) as week, DATE(visited_at) AS day, COUNT(*) AS visits')
        ->where('visited_at', '>=', $start->startOfDay())
        ->where('visited_at', '<=', $end->endOfDay())
        ->groupBy('month', 'week', 'day')
        ->latest('day')
        ->get();
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
            ->wherePatronCategoryId($category)
            ->latest('visited_at')
            ->first()) {
            $entry->delete();
        }
    }
}
