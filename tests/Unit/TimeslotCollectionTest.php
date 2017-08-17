<?php

namespace Tests\Unit;

use App\Timeslot;
use Carbon\Carbon;
use Tests\TestCase;
use App\TimeslotCollection;

class TimeslotCollectionTest extends TestCase
{
    public function setUp()
    {
        $startDate = '2017-01-17 09:00:00';
        $slots = 8;
        $this->collection = TimeslotCollection::make($startDate, $slots);
    }

    /** @test */
    public function create_a_timeslot_collection()
    {
        $this->assertInstanceOf('App\TimeslotCollection', $this->collection);
        $this->assertInstanceOf('Carbon\Carbon', $this->collection->start());
        $this->assertInstanceOf('Carbon\Carbon', $this->collection->end());
    }

    /** @test */
    public function start_and_end_are_calculated_properly()
    {
        $this->assertEquals('2017-01-17 09:00:00', $this->collection->start()->toDateTimeString());
        $this->assertEquals('2017-01-17 16:59:59', $this->collection->end()->toDateTimeString());
    }

    /** @test */
    public function it_contains_an_array_of_timeslots()
    {
        $timeslotCollection = [
            Timeslot::custom(Carbon::parse('2017-01-17 09:00:00')),
            Timeslot::custom(Carbon::parse('2017-01-17 10:00:00')),
            Timeslot::custom(Carbon::parse('2017-01-17 11:00:00')),
            Timeslot::custom(Carbon::parse('2017-01-17 12:00:00')),
            Timeslot::custom(Carbon::parse('2017-01-17 13:00:00')),
            Timeslot::custom(Carbon::parse('2017-01-17 14:00:00')),
            Timeslot::custom(Carbon::parse('2017-01-17 15:00:00')),
            Timeslot::custom(Carbon::parse('2017-01-17 16:00:00')),
        ];

        $this->assertArraySubset($timeslotCollection, $this->collection->getCollection());
    }
}
