<?php

namespace Tests\Feature;

use App\Timeslot;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateLogbookEntryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function an_unauthenticated_user_cannot_create_logbook_entries()
    {
        $this->withExceptionHandling();

        $entry = make('App\Logbook\Entry');

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry->toArray()]])
            ->assertRedirect('/login');
    }
    
    /** @test */
    public function an_authenticated_user_can_create_logbook_entries()
    {
        $this->signIn();

        $timeslot = Timeslot::now()->addHour();

        $entry1 = make('App\Logbook\Entry');
        $entry2 = make('App\Logbook\Entry', [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end(),
        ]);

        $this->post('/logbook', ['entry' => [
            'any_entry_id' => $entry1->toArray(),
            'another_entry_id' => $entry2->toArray()
        ]]);

        // TODO: This should assert that the date and count are visible on the /logbook/show?day=yyyy-mm-dd page 
        $this->get('/logbook')
            ->assertSee($entry1->start_time->toDateString())
            ->assertSee($entry1->patronCategory->name)
            ->assertSee($entry2->start_time->toDateString())
            ->assertSee($entry2->patronCategory->name);
    }

    /** @test */
    public function the_count_value_must_be_a_valid_positive_integer()
    {
        $this->withExceptionHandling()->signIn();

        $entry = make('App\Logbook\Entry', ['count' => -999]);

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry->toArray()]])
            ->assertRedirect(route('logbook.create'))
            ->assertSessionHasErrors('entry.*.count');
    }

    /** @test */
    public function the_entry_start_date_cannot_be_in_the_future()
    {
        $this->withExceptionHandling()->signIn();

        $timeslot = Timeslot::custom(Carbon::tomorrow());

        $entry = make('App\Logbook\Entry', [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end()
        ]);

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry->toArray()]])
            ->assertRedirect(route('logbook.create'))
            ->assertSessionHasErrors('entry.*.start_time');
    }

    // TEST addCount(): it adds a count for the current category in the current timeslot
    // TEST removeCount(): it removes a count for the current category in the current timeslot
    // and avoids a negative count
    // TEST removeCount(): if count < 1, it deletes the record from the database
}
