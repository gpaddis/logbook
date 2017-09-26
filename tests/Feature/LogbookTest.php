<?php

namespace Tests\Feature;

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
    public function it_gets_the_visits_count_by_day()
    {
        $this->signIn();
        $timeslot = Timeslot::create('2017-08-10 10:00:00');

        create('App\LogbookEntry', [
            'visited_at' => $timeslot->start()->subDay()
        ], 5);

        create('App\LogbookEntry', [
            'visited_at' => $timeslot->start()
        ], 3);

        create('App\LogbookEntry', [
            'visited_at' => $timeslot->start()->addDay()
        ], 6);

        create('App\LogbookEntry', [
            'visited_at' => $timeslot->start()->addDays(2)
        ], 5);

        $result = LogbookEntry::getCountByDay();

        $this->assertEquals([
            '2017-08-09' => 5,
            '2017-08-10' => 3,
            '2017-08-11' => 6,
            '2017-08-12' => 5
        ], $result->toArray());
        $this->assertInstanceOf('Illuminate\Support\Collection', $result);
    }
}
