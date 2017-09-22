<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LiveCounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_adds_a_visit_to_the_database()
    {
        $this->signIn();

        $patronCategory = create('App\PatronCategory');

        $this->post('/logbook/livecounter/add', [
            'patron_category_id' => $patronCategory->id
            ]);

        $this->assertDatabaseHas('logbook_entries', [
            'patron_category_id' => $patronCategory->id,
            'visited_at' => Carbon::now(),
            'recorded_live' => true
            ]);
    }

    /** @test */
    public function it_deletes_the_last_entry_from_the_database()
    {
        $this->signIn();

        $patronCategories = create('App\PatronCategory', [], 3);

        $entry1 = create('App\LogbookEntry', [
                'patron_category_id' => $patronCategories[0]->id
            ]);

        $entry2 = create('App\LogbookEntry', [
                'patron_category_id' => $patronCategories[1]->id
            ]);

        $entry3 = create('App\LogbookEntry', [
                'patron_category_id' => $patronCategories[1]->id,
                'visited_at' => Carbon::now()->addMinute()
            ]);

        $this->post('/logbook/livecounter/subtract', [
            'patron_category_id' => $patronCategories[1]->id
            ]);

        $this->assertDatabaseMissing('logbook_entries', $entry3->toArray());
    }
}
