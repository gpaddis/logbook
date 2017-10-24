<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\LogbookEntry;
use Timeslot\Timeslot;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogbookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_cannot_create_logbook_entries()
    {
        $this->withExceptionHandling();

        $this->post('/logbook', ['anything'])
        ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_logbook_entries()
    {
        $this->signIn();

        $entry = create('App\LogbookEntry');

        $this->assertDatabaseHas('logbook_entries', $entry->toArray());
    }

    /** @test */
    public function it_filters_entries_within_a_given_timeslot()
    {
        $this->signIn();
        $timeslot = Timeslot::create('2017-08-10 10:00:00');

        create('App\LogbookEntry', [
            'visited_at' => $timeslot->start()->subHour()
            ], 5);

        $target = create('App\LogbookEntry', [
            'visited_at' => $timeslot->start()
            ], 3);

        create('App\LogbookEntry', [
            'visited_at' => $timeslot->start()->addHour()
            ], 6);

        $result = LogbookEntry::withinTimeslot($timeslot)->get();

        $this->assertCount(3, $result);
    }

    /** @test */
    public function it_gets_the_aggregate_values_within_a_time_range()
    {
        $this->signIn();
        $timeslot = Timeslot::create('2017-08-10 10:00:00');

        create('App\LogbookEntry', [
            'visited_at' => '2017-08-10 10:00:00'
            ], 5);

        create('App\LogbookEntry', [
            'visited_at' => '2017-08-11 00:00:00'
            ], 3);

        create('App\LogbookEntry', [
            'visited_at' => '2017-08-12 10:00:00'
            ], 6);

        create('App\LogbookEntry', [
            'visited_at' => '2017-08-13 23:59:59'
            ], 5);

        $result = LogbookEntry::getAggregatesWithin(Carbon::parse('2017-08-11'), Carbon::parse('2017-08-13'));

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $result);
        $this->assertEquals(3, $result->where('day', '2017-08-11')->first()->visits);
        $this->assertEquals(5, $result->where('day', '2017-08-13')->first()->visits);
        $this->assertEquals(4.7, number_format($result->pluck('visits')->average(), 1));
    }

    /** @test */
    public function it_returns_only_entries_within_a_specific_year()
    {
        create('App\LogbookEntry', [
            'visited_at' => Carbon::now()->subYear()
            ], 10);

        factory('App\LogbookEntry', 9)->create();

        $this->assertCount(9, LogbookEntry::year(Carbon::now()->year)->get());
    }

    /** @test */
    public function it_returns_the_entries_for_the_browse_year_tab()
    {
        create('App\LogbookEntry', [
            'visited_at' => '2015-01-02 12:00:00'
            ], 5);

        create('App\LogbookEntry', [
            'visited_at' => '2016-01-02 12:00:00'
            ], 5);

        create('App\LogbookEntry', [
            'visited_at' => '2017-01-02 12:00:00'
            ], 5);

        $visits2017 = LogbookEntry::getYearData(2017, 1);
        $this->assertTrue($visits2017->contains('year', 2017));
        $this->assertFalse($visits2017->contains('year', 2015));
        $this->assertFalse($visits2017->contains('year', 2016));
        $this->assertCount(5, $visits2017);

        $visits2016 = LogbookEntry::getYearData(2016, 1);
        $this->assertTrue($visits2016->contains('year', 2016));
        $this->assertFalse($visits2016->contains('year', 2015));
        $this->assertFalse($visits2016->contains('year', 2017));
        $this->assertCount(5, $visits2016);

        $twoYears = LogbookEntry::getYearData(2017, 2);
        $this->assertTrue($twoYears->contains('year', 2016));
        $this->assertTrue($twoYears->contains('year', 2017));
        $this->assertFalse($twoYears->contains('year', 2015));
        $this->assertCount(10, $twoYears);
    }
}
