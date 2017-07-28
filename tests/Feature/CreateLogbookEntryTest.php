<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateLogbookEntryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function an_unauthenticated_user_cannot_create_logbook_entries()
    {
        $this->withExceptionHandling();

        $entry = make('App\Logbook\Entry');

        $this->post('/logbook', [
            'entry' => [
                $entry->start_time->timestamp . $entry->patron_category_id => $entry->toArray()
            ]
        ])->assertRedirect('/login');
    }
    
    /** @test */
    public function an_authenticated_user_can_create_logbook_entries()
    {
        $this->signIn();

        $entry1 = make('App\Logbook\Entry');
        $entry2 = make('App\Logbook\Entry', [
            'start_time' => \App\Timeslot::now()->addHour()->start(),
            'end_time' => \App\Timeslot::now()->addHour()->end(),
        ]);

        $this->post('/logbook', [
            'entry' => [
                $entry1->start_time->timestamp . $entry1->patron_category_id => $entry1->toArray(),
                $entry2->start_time->timestamp . $entry2->patron_category_id => $entry2->toArray()
                ]
        ]);

        // TODO: This should assert that the date and count are visible on the /logbook/day/{date} page 
        $this->get('/logbook')
            ->assertSee($entry1->start_time->toDateString())
            ->assertSee($entry2->start_time->toDateString())
            ->assertSee($entry1->patronCategory->name)
            ->assertSee($entry2->patronCategory->name);
    }

    /** @test */
    public function the_count_value_must_be_a_valid_integer()
    {
        $this->withExceptionHandling()->signIn();

        $entry = make('App\Logbook\Entry', ['count' => -12]);

        $response = $this->post('/logbook', ['entry' => [$entry->start_time->timestamp . $entry->patron_category_id => $entry->toArray()]]);
        
        $response->assertRedirect(route('logbook.create'))
            ->assertSessionHasErrors('entry.*.count');
    }

    /** @test */
    public function the_entry_date_cannot_be_in_the_future()
    {
        $this->withExceptionHandling()->signIn();

        $entry = make('App\Logbook\Entry', [
            'start_time' => \Carbon\Carbon::tomorrow(),
            'end_time' => \Carbon\Carbon::tomorrow()->addHour()
        ]);

        $response = $this->post('/logbook', ['entry' => 
            [$entry->start_time->timestamp . $entry->patron_category_id => $entry->toArray()]
        ]);
        
        $response->assertRedirect(route('logbook.create'))
            ->assertSessionHasErrors('entry.*.start_time');

    }

    // TEST addCount(): it adds a count for the current category in the current timeslot
    // TEST removeCount(): it removes a count for the current category in the current timeslot
    // and avoids a negative count
    // TEST removeCount(): if count < 1, it deletes the record from the database
}
