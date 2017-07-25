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
        $log = factory('App\Logbook\Entry')->create();

        // Start and end time are instance of the Carbon class
        $this->assertInstanceOf(Carbon::class, $log->start_time);
        $this->assertInstanceOf(Carbon::class, $log->end_time);

        // The count is an integer
        $this->assertInternalType("int", $log->count);

        // The patron category just created exists in the database
        $patronCategory = \App\PatronCategory::first();

        $this->assertEquals($patronCategory->id, $log->patron_category_id);
    }

    // TEST addCount(): it adds a count for the current category in the current timeslot
    // TEST removeCount(): it removes a count for the current category in the current timeslot
    // and avoids a negative count
    // TEST removeCount(): if count < 1, it deletes the record from the database
}
