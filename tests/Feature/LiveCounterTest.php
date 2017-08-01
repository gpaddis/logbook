<?php

namespace Tests\Feature;

use App\Timeslot;
use Tests\TestCase;
use App\Logbook\Entry;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LiveCounterTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_add_a_visit_count()
    {
        $this->signIn();

        $patronCategory = create('App\PatronCategory');

        $this->get('/logbook/store?id='. $patronCategory->id .'&operation=add');

        $entry = Entry::first();
        $timeslot = Timeslot::now();

        // Check whether the data was stored correctly
        $this->assertEquals($patronCategory->id, $entry->patron_category_id);
        $this->assertEquals(1, $entry->count);
        $this->assertEquals($timeslot->start(), $entry->start_time);
        $this->assertEquals($timeslot->end(), $entry->end_time);
    }
}
