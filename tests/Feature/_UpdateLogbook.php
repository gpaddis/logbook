<?php

namespace Tests\Feature;

use Timeslot\Timeslot;
use Tests\TestCase;
use App\Logbook\Entry;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateLogbookTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_unauthenticated_user_cannot_create_logbook_entries()
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

        $timeslot = Timeslot::after(Timeslot::now());

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
    public function the_count_value_must_be_less_or_equal_to_65535()
    {
        $this->withExceptionHandling()->signIn();

        $entry = make('App\Logbook\Entry', ['visits' => 99999999999999]);

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry->toArray()]])
        ->assertSessionHasErrors('entry.*.visits');
    }

    /** @test */
    public function it_displays_the_form_for_the_date_requested()
    {
        $this->signIn()->get('/logbook/update?date=2017-08-18')
            ->assertSee('Update the logbook for Aug 18, 2017');
    }

    /** @test */
    public function an_invalid_date_does_not_pass_validation()
    {
        $this->signIn()->withExceptionHandling()
            ->get('/logbook/update?date=invalid-string')
            ->assertSessionHasErrors('date');
    }

    /** @test */
    public function the_form_shows_data_already_stored_in_the_database()
    {
        $this->signIn();

        $timeslot = Timeslot::create('1985-02-13 12:00:00');

        $entry = create('App\Logbook\Entry', [
            'visits' => 1234567890,
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end()
            ]);

        $this->get('/logbook/update?date=1985-02-13')
            ->assertSee('value="' . $entry->visits . '"');
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
            'visits' => 0
            ]);

        $this->post('/logbook', ['entry' => ['any_entry_id' => $zeroEntry->toArray()]]);
        $this->assertEquals(null, Entry::where('start_time', $storedEntry->start_time)->first());
    }

    /** @test */
    public function it_does_nothing_if_we_sumbit_a_0_for_a_nonexisting_entry()
    {
        $this->signIn()->withExceptionHandling();

        $zeroEntry = make('App\Logbook\Entry', ['visits' => 0]);
        $response = $this->post('/logbook', ['entry' => ['any_entry_id' => $zeroEntry->toArray()]]);

        $this->assertEquals(null, Entry::where('start_time', $zeroEntry->start_time)->first());
        $response->assertRedirect('/logbook');
    }
}
