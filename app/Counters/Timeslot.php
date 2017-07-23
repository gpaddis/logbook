<?php

namespace App\Counters;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Timeslot
 */
class Timeslot
{
    protected $start;
    protected $hours;
    protected $end;

    protected function __construct(Carbon $start, int $hours)
    {
        $this->start = $start;
        $this->hours = $hours;
    }

    // Setter methods
    protected function setStart($start)
    {
        $this->start = $start->minute(0)->second(0);
    }

    protected function setEnd($hours)
    {
        // If the interval is 1 hour, set it to 0 hours, 59 mins and 59 secs
        $hours -= 1;

        $this->end = clone $this->start;
        $this->end->addHours($hours)->minute(59)->second(59);
    }

    /**
     * Add a number of $hours to the timeslot
     * @param integer $hours
     */
    public function addHour(int $hours = 1)
    {
        $this->start->addHour($hours);
        $this->end->addHour($hours);

        return $this;
    }

    // Getter methods
    public function start()
    {
        return $this->start;
    }

    public function end()
    {
        return $this->end;
    }

    /**
     * Get an array of start and end.
     * 
     * @return array
     */
    public function get()
    {
        return [
            'start' => $this->start(),
            'end' => $this->end()
        ];
    }

    // Static methods
    public static function now($hours = 1)
    {
        return self::custom(Carbon::now(), $hours);
    }

    public static function custom($start, $hours)
    {
        $timeslot = new self($start, $hours);
        $timeslot->setStart($timeslot->start);
        $timeslot->setEnd($timeslot->hours);
        return $timeslot;
    }

    public static function today()
    {
        $timeslot = self::now(24);
        $timeslot->start = Carbon::now()->hour(0)->minute(0)->second(0);
        $timeslot->setEnd($timeslot->hours);
        return $timeslot;
    }

    public static function thisWeek()
    {
        $timeslot = self::now();
        $timeslot->start = Carbon::now()->startOfWeek();
        $timeslot->end = Carbon::now()->endOfWeek();
        return $timeslot;
    }

    public static function lastWeek()
    {
        $timeslot = self::thisWeek();
        $timeslot->start->subWeek();
        $timeslot->end->subWeek();
        return $timeslot;
    }

    public static function thisMonth()
    {
        $timeslot = self::now();
        $timeslot->start = Carbon::now()->startOfMonth();
        $timeslot->end = Carbon::now()->endOfMonth();
        return $timeslot;
    }

    public static function lastMonth()
    {
        $timeslot = self::custom(Carbon::now()->subMonth(), 1);
        
        $timeslot->start->startOfMonth();
        $timeslot->end->endOfMonth();

        return $timeslot;
    }
}
