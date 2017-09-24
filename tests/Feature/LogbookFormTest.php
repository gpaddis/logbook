<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\LogbookEntry;
use Timeslot\Timeslot;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogbookFormTest extends TestCase
{
    use RefreshDatabase;

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
            'patron_category_id' => $entry1['patron_category_id'],
            'visited_at' => $entry1['start_time'],
            'recorded_live' => false
        ]);

        $this->assertDatabaseHas('logbook_entries', [
            'patron_category_id' => $entry2['patron_category_id'],
            'visited_at' => $entry2['start_time'],
            'recorded_live' => false
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

    /** @test */
    public function it_deletes_all_records_in_a_timeslot_if_0_is_submitted()
    {
        $this->signIn();

        $category = create('App\PatronCategory');
        $timeslot = Timeslot::create('2017-01-12 12:00:00');

        $visitsToDelete = create('App\LogbookEntry', [
            'patron_category_id' => $category->id,
            'visited_at' => '2017-01-12 12:08:12',
        ], 3);

        $visitToKeep = create('App\LogbookEntry', [
            'patron_category_id' => $category->id,
            'visited_at' => '2017-01-12 13:00:00'
        ]);

        $this->post('/logbook', ['entry' => ['any_entry_id' => [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end(),
            'patron_category_id' => $category->id,
            'visits' => 0
        ]]]);

        $this->assertDatabaseMissing('logbook_entries', $visitsToDelete->first()->toArray());
        $this->assertDatabaseHas('logbook_entries', $visitToKeep->toArray());
        $this->assertCount(1, LogbookEntry::all());
    }

    /** @test */
    public function it_does_nothing_if_one_submits_a_0_for_a_timeslot_with_no_records()
    {
        $this->signIn()->withExceptionHandling();

        $entry = [
            'start_time' => '1994-12-03 10:00:00',
            'end_time' => '1994-12-03 10:59:59',
            'patron_category_id' => create('App\PatronCategory')->id,
            'visits' => 0
        ];

        $this->post('/logbook', ['entry' => ['any_entry_id' => $entry]])
        ->assertRedirect('/logbook');

        $this->assertCount(0, LogbookEntry::within($entry['start_time'], $entry['end_time'])->get());
    }

    /** @test */
    public function the_form_shows_data_already_stored_in_the_database()
    {
        $this->signIn();

        create('App\LogbookEntry', ['visited_at' => '1985-02-13 12:10:04'], 32);

        $this->get('/logbook/update?date=1985-02-13')->assertSee('value="32"');
    }

    /** @test */
    public function it_preserves_part_of_the_existing_live_records_when_updating_with_a_lesser_value()
    {
        $this->signIn();

        $category = create('App\PatronCategory');

        create('App\LogbookEntry', [
            'visited_at' => '1985-02-13 12:13:14',
            'patron_category_id' => $category->id,
            'recorded_live' => true
        ], 10);

        $formContent = [
            'start_time' => '1985-02-13 12:00:00',
            'end_time' => '1985-02-13 12:59:59',
            'patron_category_id' => $category->id,
            'visits' => 5
        ];

        $this->post('/logbook', ['entry' => ['any_entry_id' => $formContent]]);

        $this->assertCount(5, LogbookEntry::all());
        $this->assertDatabaseHas('logbook_entries', [
            'visited_at' => '1985-02-13 12:13:14',
            'recorded_live' => true
        ]);
    }
}
