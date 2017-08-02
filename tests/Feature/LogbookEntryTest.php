<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Timeslot;
use App\Logbook\Entry;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LogbookEntryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function the_default_visits_log_is_well_formed()
    {
        $entry = create('App\Logbook\Entry');

        // Start and end time are instance of the Carbon class
        $this->assertInstanceOf(Carbon::class, $entry->start_time);
        $this->assertInstanceOf(Carbon::class, $entry->end_time);

        // The count is an integer
        $this->assertInternalType("int", $entry->count);

        // The patron category just created is persisted in the database
        $patronCategory = \App\PatronCategory::first();

        $this->assertEquals($patronCategory->id, $entry->patron_category_id);
    }

    /** @test */
    public function it_can_record_a_visit_if_it_does_not_yet_exist()
    {
        $timeslot = Timeslot::now();
        $patron_category_id = create('App\PatronCategory')->id;

        Entry::add($patron_category_id, $timeslot);

        $this->assertEquals(1, Entry::first()->count);
    }

    /** @test */
    public function it_adds_a_visit_to_the_count_if_the_entry_already_exists()
    {
        $timeslot = Timeslot::now();
        $existingEntry = create('App\Logbook\Entry', [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end()
        ]);

        Entry::add($existingEntry->patron_category_id, $timeslot);

        $this->assertEquals($existingEntry->count + 1, Entry::first()->count);
    }
}
