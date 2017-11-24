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
     * @return Builder
     */
    public function scopeYear($query, int $year)
    {
        return $query->whereYear('visited_at', $year);
    }

    /**
     * Scope a query to only include logbook entries within the given month.
     *
     * @param  Builder $query
     * @param  int     $month
     *
     * @return Builder
     */
    public function scopeMonth($query, int $month)
    {
        return $query->whereMonth('visited_at', $month);
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
     * Scope the query to include only logbook entries of the day specified.
     *
     * @param  Builder $query
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeDay($query, $day)
    {
        return $query->whereDay('visited_at', $day);
    }

    /**
     * Scope the query to include only today's logbook entries.
     *
     * @param  Builder $query
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
     * Get the number of days containing logbook entries in a given year.
     *
     * @param  int $year
     *
     * @return int
     */
    public static function getOpeningDays($year)
    {
        return static::year($year)
        ->selectRaw('DATE(visited_at) as day')
        ->distinct('days')
        ->get()
        ->count();
    }

    /**
     * Get the total number of visits grouped by year and month, with an optional second year for comparison.
     *
     * @param  int $year1
     * @param  int $year2
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getTotalVisitsByYear(int $year1, int $year2 = null)
    {
        $collection = static::selectRaw('YEAR(visited_at) as year, MONTH(visited_at) as month, count(*) as visits')
        ->groupBy('year', 'month')
        ->having('year', $year1)
        ->orHaving('year', $year2)
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

    /**
     * Get the total number of visits for the year specified, grouped by patron category.
     *
     * @param  int    $year
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getTotalVisitsByPatronCategory(int $year)
    {
        return static::year($year)
        ->selectRaw('count(*) as visits, patron_category_id')
        ->groupBy('patron_category_id')
        ->with('patronCategory')
        ->get()
        ->pluck('visits', 'patronCategory.name');
    }

    /**
     * Get the visits count for the last available day before today.
     *
     * @return Illuminate\Database\Eloquent\Collection | null
     */
    public static function lastAvailableDay()
    {
        return static::whereDate('visited_at', '<', date('Y-m-d'))
        ->selectRaw('DATE(visited_at)as date, count(*) as visits')
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->first();
    }

    /**
     * Group the visits by month.
     *
     * @param Builder $builder
     * @return Collection
     */
    public function scopeGroupVisitsByMonth($builder)
    {
        return $builder
            ->selectRaw('MONTH(visited_at) as month, count(*) as visits')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('visits', 'month');
    }

    /**
     * Group the visits by day.
     *
     * @param Builder $builder
     * @return Collection
     */
    public function scopeGroupVisitsByDay($builder)
    {
        return $builder
            ->selectRaw('DAY(visited_at) as day, count(*) as visits')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('visits', 'day');
    }

    /**
     * Group the visits by hour.
     *
     * @param Builder $builder
     * @return Collection
     */
    public function scopeGroupVisitsByHour($builder)
    {
        return $builder
            ->selectRaw('HOUR(visited_at) as hour, count(*) as visits')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('visits', 'hour');
    }
}
