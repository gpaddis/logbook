<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\LogbookEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogbookFormTest extends TestCase
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
    public function it_persists_several_logbook_entries_simultaneously()
    {
        $this->signIn();

        create('App\PatronCategory', [], 2);

        $entry1 = [
        'start_time' => '2017-08-21 12:00:00',
        'end_time' => '2017-08-21 13:00:00',
        'patron_category_id' => 1,
        'visits' => 8
        ];

        $entry2 = [
        'start_time' => '2017-08-21 12:00:00',
        'end_time' => '2017-08-21 13:00:00',
        'patron_category_id' => 2,
        'visits' => 2
        ];

        $this->post('/logbook', ['entry' => [
            'any_entry_id' => $entry1,
            'another_entry_id' => $entry2
            ]]);

        $this->assertDatabaseHas('logbook_entries', [
            'patron_category_id' => $entry1['patron_category_id']
            ]);
        $this->assertDatabaseHas('logbook_entries', [
            'patron_category_id' => $entry2['patron_category_id']
            ]);
        $this->assertCount(10, LogbookEntry::all());
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

        $entry = [
        'start_time' => '2017-08-21 12:00:00',
        'end_time' => '2017-08-21 13:00:00',
        'patron_category_id' => 1,
        'visits' => null
        ];

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry]])
        ->assertSessionHasErrors('empty-form');
    }

    /** @test */
    public function the_count_value_must_be_a_valid_positive_integer()
    {
        $this->withExceptionHandling()->signIn();

        $entry = [
        'start_time' => '2017-08-21 12:00:00',
        'end_time' => '2017-08-21 13:00:00',
        'patron_category_id' => create('App\PatronCategory')->id,
        'visits' => -999
        ];

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry]])
        ->assertSessionHasErrors('entry.*.visits');
    }

    /** @test */
    public function it_does_not_pass_validation_if_visited_at_is_in_the_future()
    {
        $this->withExceptionHandling()->signIn();

        $entry = [
        'start_time' => Carbon::now()->addHour(),
        'end_time' => Carbon::now()->addHours(2),
        'patron_category_id' => create('App\PatronCategory')->id,
        'visits' => null
        ];

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry]])
        ->assertSessionHasErrors('entry.*.start_time');
    }
}
