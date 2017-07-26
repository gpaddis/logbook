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
}
