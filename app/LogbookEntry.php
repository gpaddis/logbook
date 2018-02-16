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
     * Cast the field visited_at to a Carbon instance automatically.
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
     * @return Builder
     */
    public function scopeWithin($query, string $start, string $end)
    {
        return $query->where('visited_at', '>=', $start)
            ->where('visited_at', '<=', $end);
    }

    /**
     * Scope a query to only include logbook entries within the given year.
     *
     * @param Builder $query
     * @param int $year
     * @return Builder
     */
    public function scopeYear($query, int $year)
    {
        return $query->whereYear('visited_at', $year);
    }

    /**
     * Scope a query to only include logbook entries within the given month.
     *
     * @param Builder $query
     * @param int $month
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
     * @param Builder $query
     * @param TimeslotInterface $timeslot
     * @return Builder
     */
    public function scopeWithinTimeslot($query, TimeslotInterface $timeslot)
    {
        return $query->where('visited_at', '>=', $timeslot->start())
            ->where('visited_at', '<=', $timeslot->end());
    }

    /**
     * Scope the query to include only logbook entries of the day specified.
     *
     * @param Builder $query
     * @param int $day
     * @return Builder
     */
    public function scopeDay($query, $day)
    {
        return $query->whereDay('visited_at', $day);
    }

    /**
     * Scope the query to include only today's logbook entries.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', date('Y-m-d'));
    }

    /**
     * A logbook entry belongs to a patron category.
     *
     * @return BelongsTo
     */
    public function patronCategory()
    {
        return $this->belongsTo(PatronCategory::class);
    }

    /**
     * Delete today's most recent logbook entry for the given patron_category_id.
     *
     * @param  int      $category
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
     * Count the unique number of days in a collection of entries.
     *
     * @param Builder $builder
     * @return int
     */
    public function scopeCountDays($builder)
    {
        return $builder->get()
            ->groupBy(function ($entry) {
                return $entry->visited_at->toDateString();
            })->count();
    }

    /**
     * Get the visits count for the last available day before today.
     *
     * @return Collection | null
     */
    public static function lastAvailableDay()
    {
        return static::whereDate('visited_at', '<', date('Y-m-d'))
            ->selectRaw('DATE(visited_at) as date, count(*) as visits')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->first();
    }

    /**
     * Aggregate the entries according to the desired period (month, day, hour).
     *
     * @param Builder $builder
     * @param string $period
     * @return array
     */
    public function scopeAggregateBy($builder, string $period)
    {
        $allowedPeriods = ['month', 'day', 'hour'];

        if (!in_array($period, $allowedPeriods)) {
            return [];
        }

        $entries = $builder
            ->selectRaw("{$period}(visited_at) as {$period}, count(*) as visits")
            ->groupBy($period)
            ->orderBy($period)
            ->get();

        $result = [];
        foreach ($entries as $entry) {
            if (!isset($result[$entry->$period])) {
                $result[$entry->$period] = $entry->visits;
            } else {
                $result[$entry->$period] += $entry->visits;
            }
        }

        return $result;
    }

    /**
     * Aggregate the entries and format the query to make it exportable.
     *
     * @param Builder $builder
     * @return array
     */
    public function scopeExport($builder)
    {
        $entries = $builder->oldest('visited_at')->get()
            ->groupBy(function ($item) {
                return $item->visited_at->format('Y-m-d H:00:00');
            })->map(function ($collection) {
                return $collection->groupBy('patronCategory.name');
            });

        $visits = [];
        foreach ($entries as $datetime => $categories) {
            foreach ($categories as $category => $records) {
                $visits[] = [
                    'start_time' => $datetime,
                    'category' => $category,
                    'visits' => count($records)
                ];
            }
        }

        return $visits;
    }
}
