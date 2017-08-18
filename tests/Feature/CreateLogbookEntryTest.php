<?php

namespace Tests\Feature;

use App\Timeslot;
use Carbon\Carbon;
use Tests\TestCase;
use App\Logbook\Entry;
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

        $response = $this->post('/logbook', ['entry' => [
            'any_entry_id' => $entry1->toArray(),
            'another_entry_id' => $entry2->toArray()
            ]]);

        // TODO: This should assert that the date and count are visible on the /logbook/show?day=yyyy-mm-dd page
        $this->get('/logbook')
        ->assertSee($entry1->start_time->toDateString())
        ->assertSee($entry1->patron_category->name)
        ->assertSee($entry2->start_time->toDateString())
        ->assertSee($entry2->patron_category->name);
    }

    /** @test */
    public function it_displays_a_warning_if_there_are_no_active_patron_categories()
    {
        $this->signIn();

        $this->get('/logbook/update')
        ->assertSee('It looks like there are no active patron categories yet');
    }

    /** @test */
    public function it_cannot_submit_an_empty_form()
    {
        $this->withExceptionHandling()->signIn();

        $entry = make('App\Logbook\Entry', ['visits_count' => null]);

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry->toArray()]])
        ->assertSessionHasErrors('empty-form');
    }

    /** @test */
    public function the_count_value_must_be_a_valid_positive_integer()
    {
        $this->withExceptionHandling()->signIn();

        $entry = make('App\Logbook\Entry', ['visits_count' => -999]);

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry->toArray()]])
        ->assertSessionHasErrors('entry.*.visits_count');
    }

    /** @test */
    public function start_time_and_end_time_must_be_different()
    {
        $this->withExceptionHandling()->signIn();

        $now = Carbon::now();

        $entry = make('App\Logbook\Entry', [
            'start_time' => $now,
            'end_time' => $now
            ]);

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry->toArray()]])
        ->assertSessionHasErrors('entry.*.end_time');
    }

    /** @test */
    public function the_start_date_cannot_be_in_the_future()
    {
        $this->withExceptionHandling()->signIn();

        $timeslot = Timeslot::custom(Carbon::tomorrow());

        $entry = make('App\Logbook\Entry', [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end()
            ]);

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry->toArray()]])
        ->assertSessionHasErrors('entry.*.start_time');
    }

    /** @test */
    public function the_form_shows_data_already_stored_in_the_database()
    {
        $this->signIn();

        // TODO: adapt this test when the function to update the logbook for a specific date
        // is implemented.
        $timeslot = Timeslot::custom(Carbon::now()->hour(12));

        $entry = create('App\Logbook\Entry', [
            'visits_count' => 1234567890,
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end()
            ]);

        $this->get('/logbook/update')
        ->assertSee((string) $entry->visits_count);
    }

    /** @test */
    public function it_deletes_an_entry_if_a_0_is_submitted()
    {
        $this->signIn();
        $storedEntry = create('App\Logbook\Entry');

        $zeroEntry = make('App\Logbook\Entry', [
            'start_time' => $storedEntry->start_time,
            'end_time' => $storedEntry->end_time,
            'patron_category_id' => $storedEntry->patron_category_id,
            'visits_count' => 0
            ]);

        $this->post('/logbook', ['entry' => ['any_entry_id' => $zeroEntry->toArray()]]);
        $this->assertEquals(null, Entry::where('start_time', $storedEntry->start_time)->first());
    }

    /** @test */
    public function it_does_nothing_if_we_sumbit_a_0_for_a_nonexisting_entry()
    {
        $this->signIn()->withExceptionHandling();

        $zeroEntry = make('App\Logbook\Entry', ['visits_count' => 0]);
        $response = $this->post('/logbook', ['entry' => ['any_entry_id' => $zeroEntry->toArray()]]);

        $this->assertEquals(null, Entry::where('start_time', $zeroEntry->start_time)->first());
        $response->assertRedirect('/logbook');
    }
}
