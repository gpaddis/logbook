<?php

namespace App\Logbook;

use App\Timeslot;
use App\PatronCategory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logbook';

    protected $fillable = [
    'start_time',
    'end_time',
    'patron_category_id',
    'visits'
    ];

    /**
     * Related models to be eager loaded each time an Entry model is loaded.
     *
     * @var array
     */
    // protected $with = ['patron_category'];

    /**
     * A logbook entry belongs to a patron category.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patron_category()
    {
        return $this->belongsTo('App\PatronCategory');
    }

    /**
     * Persist an entry in the database only if the count is not null.
     *
     * @param  array $entry
     * @return
     */
    public static function updateOrCreateIfNotNull(array $entry)
    {
        if($entry['visits'] == 0) return static::deleteZeroes($entry);

        if ($entry['visits'] !== null) {
            Entry::updateOrCreate(
                [
                'start_time' => $entry['start_time'],
                'end_time' => $entry['end_time'],
                'patron_category_id' => $entry['patron_category_id']
                ],
                [
                'visits' => $entry['visits']
                ]);
        }
    }

    /**
     * Delete an entry in the database if the count is zero.
     *
     * @param  array $entry
     * @return
     */
    public static function deleteZeroes(array $entry)
    {
        Entry::where('start_time', $entry['start_time'])
            ->where('end_time', $entry['end_time'])
            ->where('patron_category_id', $entry['patron_category_id'])
            ->delete();
    }

    /////////////////////////////////////////////////
    ///////////////// LIVE COUNTER //////////////////
    /////////////////////////////////////////////////

    /**
     * Increment the count for a given timeslot and category by one.
     *
     * @param int $patron_category_id
     * @param App\Timeslot $timeslot
     */
    public static function add($patron_category_id, $timeslot)
    {
        $existingEntry = Entry::where('start_time', $timeslot->start())
        ->where('end_time', $timeslot->end())
        ->where('patron_category_id', $patron_category_id)
        ->first();

        $visits = 0;

        if ($existingEntry) {
            $visits = $existingEntry->visits;
        }

        Entry::updateOrCreate([
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end(),
            'patron_category_id' => $patron_category_id
            ],
            [
            'visits' => ++$visits
            ]);
    }

    public static function subtract($patron_category_id, $timeslot)
    {
        $entry = Entry::where('start_time', $timeslot->start())
        ->where('end_time', $timeslot->end())
        ->where('patron_category_id', $patron_category_id)
        ->first();

        if ($entry == null) return;

        if ($entry->visits <= 1) {
            $entry->delete();
            return;
        }

        $entry->visits -= 1;
        $entry->save();
    }


    /**
     * Scope a query to only include logbook entries within the custom timeslot.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Timeslot  $timeslot
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithinTimeslot($query, $timeslot = null)
    {
        $timeslot = $timeslot ?: Timeslot::now();

        return $query->where('start_time', $timeslot->start())
        ->where('end_time', $timeslot->end());
    }
}
