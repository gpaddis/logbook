<?php

namespace Tests\Feature;

use Timeslot\Timeslot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateLogbookTest extends TestCase
{
    use DatabaseMigrations;

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
