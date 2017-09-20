<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogbookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_cannot_create_logbook_entries()
    {
        $this->withExceptionHandling();

        $entry = make('App\LogbookEntry');

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry->toArray()]])
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
    public function it_does_not_pass_validation_if_visited_at_is_in_the_future()
    {
        $this->withExceptionHandling()->signIn();

        $entry = [
        'patron_category_id' => create('App\PatronCategory')->id,
        'visited_at' => Carbon::now()->addMinute(),
        'visits' => 1
        ];

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry]])
        ->assertSessionHasErrors('entry.*.visited_at');
    }
}
