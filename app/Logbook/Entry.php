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
        'count'
    ];
    
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
     * Persist an entry in the database only if the count is not null.
     * 
     * @param  array $entry
     * @return
     */
    public static function updateOrCreateIfNotNull(array $entry)
    {
        if ($entry['count'] !== null) {
            Entry::updateOrCreate(
                [
                    'start_time' => $entry['start_time'],
                    'end_time' => $entry['end_time'],
                    'patron_category_id' => $entry['patron_category_id']
                ],
                [
                    'count' => $entry['count']
                ]);
        }
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

        $count = 0;

        if ($existingEntry) {
            $count = $existingEntry->count;
        }

        Entry::updateOrCreate([
                'start_time' => $timeslot->start(),
                'end_time' => $timeslot->end(),
                'patron_category_id' => $patron_category_id
            ],
        [
            'count' => ++$count
        ]);
    }

    /**
     * Return a string with the unique timeslot + category identifier (for the form).
     * 
     * @param  App\Timeslot       $timeslot
     * @param  App\PatronCategory $category
     * @return string
     */
    public static function identifier(Timeslot $timeslot, PatronCategory $category)
    {
        return "{$timeslot->start()->timestamp}{$category->id}";
    }
}