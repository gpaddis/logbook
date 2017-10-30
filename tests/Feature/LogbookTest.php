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
    public function it_returns_the_number_of_days_with_entries_in_the_db()
    {
        create('App\LogbookEntry', ['visited_at' => '2017-01-12 10:00:00']);
        create('App\LogbookEntry', ['visited_at' => '2017-01-13 10:00:00']);
        create('App\LogbookEntry', ['visited_at' => '2017-01-14 10:00:00']);

        $this->assertEquals(3, LogbookEntry::getOpeningDays(2017));
    }

    /** @test */
    public function it_returns_the_total_visits_collected_by_year_and_month()
    {
        create('App\LogbookEntry', [
            'visited_at' => '2015-01-02 12:00:00',
        ], 5);

        $visits2015 = LogbookEntry::getTotalVisitsByYear(2015);

        $this->assertEquals($visits2015->toArray(), [
            2015 => [
                1 => 5,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0,
                10 => 0,
                11 => 0,
                12 => 0
            ]]);

        create('App\LogbookEntry', [
            'visited_at' => '2017-05-02 12:00:00',
        ], 5);

        create('App\LogbookEntry', [
            'visited_at' => '2017-04-02 12:00:00',
        ], 5);

        create('App\LogbookEntry', [
            'visited_at' => '2017-06-02 12:00:00',
        ], 5);

        $bothYears = LogbookEntry::getTotalVisitsByYear(2017, 2015);

        $this->assertEquals(2017, $bothYears->keys()->first());
        $this->assertEquals($bothYears->toArray(), [
            2015 => [
                1 => 5,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0,
                10 => 0,
                11 => 0,
                12 => 0
            ],
            2017 => [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 5,
                5 => 5,
                6 => 5,
                7 => 0,
                8 => 0,
                9 => 0,
                10 => 0,
                11 => 0,
                12 => 0
            ]
        ]);
    }

    /** @test */
    public function it_returns_the_total_visits_grouped_by_patron_category()
    {
        list($cat1, $cat2, $cat3) = factory('App\PatronCategory', 3)->create();

        create('App\LogbookEntry', [
            'visited_at' => '2017-05-02 12:00:00',
            'patron_category_id' => $cat1->id
        ], 5);

        create('App\LogbookEntry', [
            'visited_at' => '2017-04-02 12:00:00',
            'patron_category_id' => $cat2->id
        ], 6);

        create('App\LogbookEntry', [
            'visited_at' => '2017-06-02 12:00:00',
            'patron_category_id' => $cat3->id
        ], 7);

        $this->assertEquals([
            $cat1->name => 5,
            $cat2->name => 6,
            $cat3->name => 7,
        ], LogbookEntry::getTotalVisitsByPatronCategory(2017)->toArray());
    }

    /** @test */
    public function it_returns_the_visits_count_for_the_latest_available_day_before_today()
    {
        create('App\LogbookEntry', [], 5);

        $this->assertNull(LogbookEntry::lastAvailableDay());

        $latest = create('App\LogbookEntry', [
            'visited_at' => '2017-06-02 12:00:00',
        ], 7);

        $this->assertEquals(7, LogbookEntry::lastAvailableDay()->visits);
        $this->assertEquals('2017-06-02', LogbookEntry::lastAvailableDay()->date);
    }
}
