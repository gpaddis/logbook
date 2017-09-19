<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Timeslot\Timeslot;
use App\Logbook\Entry;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadLogbookEntryTest extends TestCase
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
        $this->assertInternalType("int", $entry->visits);

        // The patron category just created is persisted in the database
        $patronCategory = \App\PatronCategory::first();

        $this->assertEquals($patronCategory->id, $entry->patron_category_id);
    }

    /** @test */
    public function it_filters_entries_within_a_given_timeslot()
    {
        $patronCategory = create('App\PatronCategory');
        $entry = create('App\Logbook\Entry', ['patron_category_id' => $patronCategory->id]);

        $result = Entry::withinTimeslot(Timeslot::now())->first();

        $this->assertEquals($entry->visits, $result->visits);
    }

    // /** @test */
    // public function it_filters_entries_within_a_given_timeslot_and_patron_category()
    // {
    //     $patronCategoryOne = create('App\PatronCategory');
    //     $patronCategoryTwo = create('App\PatronCategory');

    //     $entryWithPatronCategoryOne = create('App\Logbook\Entry', ['patron_category_id' => $patronCategoryOne->id]);
    //     $entryWithPatronCategoryTwo = create('App\Logbook\Entry', ['patron_category_id' => $patronCategoryTwo->id]);

    //     // If no timeslot is passed as second argument, the timeslot is now.
    //     $result = Entry::withinTimeslotAndPatronCategory($patronCategoryOne)->first();

    //     $this->assertEquals($entryWithPatronCategoryOne->visits, $result->visits);
    // }
}
