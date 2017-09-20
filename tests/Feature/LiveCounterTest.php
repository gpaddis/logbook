<?php

namespace Tests\Feature;

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
            'patron_category_id' => $patronCategory->id
            ]);
    }
}
