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
     * @param  Builder $query
     * @param  string  $start
     * @param  string  $end
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithin($query, string $start, string $end)
    {
        return $query->where('visited_at', '>=', $start)
        ->where('visited_at', '<=', $end);
    }

    /**
     * Scope a query to only include logbook entries within the given year.
     *
     * @param  Builder $query
     * @param  int     $year
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeYear($query, int $year)
    {
        return $query->whereYear('visited_at', $year);
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
     * Scope the query to include only today's logbook entries.
     *
     * @param  Builder $query
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', date('Y-m-d'));
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
        return $this->belongsTo(PatronCategory::class);
    }

    /**
     * Delete today's most recent logbook entry for the given patron_category_id.
     *
     * @param  int      $category
     *
     * @return void
     */
    public static function deleteLatestRecord(int $category)
    {
        if ($entry = static::today()
            ->wherePatronCategoryId($category)
            ->latest('visited_at')
            ->first()) {
            $entry->delete();
        }
    }

    /**
     * Get logbook entry data for the browse.year tab.
     *
     * @param  int $year
     * @param  int $depth
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getYearData(int $year, int $depth = 1)
    {
        return static::selectRaw('YEAR(visited_at) as year, MONTH(visited_at) as month, patron_category_id, count(*) as visits')
        ->with('patronCategory:id,name')
        ->groupBy('year', 'month', 'patron_category_id')
        ->having('year', '<=', $year)
        ->having('year', '>=', $year - $depth + 1)
        ->get();
    }

    /**
     * Get the total number of visits grouped by year and month.
     *
     * @param  int $year
     * @param  int $depth
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getTotalVisitsByYear(int $year, int $depth = 1)
    {
        $collection = static::selectRaw('YEAR(visited_at) as year, MONTH(visited_at) as month, count(*) as visits')
        ->groupBy('year', 'month')
        ->having('year', '<=', $year)
        ->having('year', '>=', $year - $depth + 1)
        ->get()
        ->sortByDesc('year')
        ->groupBy('year');

        // $result structure = year => [monthNo => visits, monthNo => visits, ...]
        $result = [];
        foreach ($collection as $year => $items) {
            // Create the array skeleton
            for ($monthNo = 1; $monthNo < 13 ; $monthNo++) {
                $result[$year][$monthNo] = 0;
            };

            // Replace zeroes with real values from the collection
            foreach ($items as $item) {
                $result[$year][$item->month] = $item->visits;
            }
        };

        // Collect the result to allow automatic conversion to JSON in the view.
        return collect($result);
    }
}
