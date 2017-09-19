<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogbookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_unauthenticated_user_cannot_create_logbook_entries()
    {
        $this->withExceptionHandling();

        $entry = make('App\LogbookEntry');

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry->toArray()]])
        ->assertRedirect('/login');
    }
}
