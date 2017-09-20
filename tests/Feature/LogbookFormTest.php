<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\LogbookEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogbookFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_persists_several_logbook_entries_simultaneously()
    {
        $this->signIn();

        create('App\PatronCategory', [], 2);

        $entry1 = [
        'patron_category_id' => 1,
        'visited_at' => '2017-08-21 12:00:00',
        'visits' => 8
        ];

        $entry2 = [
        'patron_category_id' => 2,
        'visited_at' => '2017-08-21 13:00:00',
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
        'patron_category_id' => 1,
        'visited_at' => '2017-08-21 12:00:00',
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
        'patron_category_id' => make('App\PatronCategory')->id,
        'visited_at' => '2017-08-21 12:00:00',
        'visits' => -999
        ];

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry]])
        ->assertSessionHasErrors('entry.*.visits');
    }
}
