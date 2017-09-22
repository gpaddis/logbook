<?php

namespace Tests\Feature;

use Timeslot\Timeslot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateLogbookTest extends TestCase
{
    use DatabaseMigrations;

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
}
