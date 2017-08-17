<?php

namespace App;

use Carbon\Carbon;

class TimeslotCollection
{
    protected $start;
    protected $end;
    protected $slots;
    protected $collection = [];

    /**
     * TimeslotCollection constructor.
     *
     * @param  Carbon\Carbon|string  $start
     * @param  integer $slots
     */
    public function __construct($start, $slots = 1)
    {
        $start instanceof Carbon ?: $start = Carbon::parse($start);

        $this->start = Timeslot::custom($start, $slots)->start();
        $this->end = Timeslot::custom($start, $slots)->end();

        $this->slots = $slots;
        $this->collection = $this->createCollection();
    }

    /**
     * Alternative static constructor
     * @param  Carbon\Carbon - string  $start
     * @param  integer $slots
     * @return TimeslotCollection
     */
    public static function make($start, int $slots = 1)
    {
        return new static($start, $slots);
    }

    /**
     * Get the start date and time of the current timeslot collection.
     *
     * @return Carbon\Carbon
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * Get the end date and time of the current timeslot collection.
     *
     * @return Carbon\Carbon
     */
    public function end()
    {
        return $this->end;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Return an array of timeslots based on start date and time and number of slots.
     *
     * @return array
     */
    public function createCollection()
    {
        $datetime = $this->start->toDateTimeString();
        $collection = [];

        for ($i = 0; $i < $this->slots; $i++) {
            $timeslot = Timeslot::custom(Carbon::parse($datetime))->addHour($i);
            array_push($collection, $timeslot);
        }

        return $collection;
    }
}
