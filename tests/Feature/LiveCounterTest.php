<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LiveCounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_may_not_submit_a_visit_count()
    {
        $this->withExceptionHandling();

        $this->post('/logbook/livecounter/add', [
            'id' => 1,
        ])->assertRedirect('login');
    }

    /** @test */
    public function it_adds_a_visit_to_the_database()
    {
        $this->signIn();

        $entry = [
            'patron_category_id' => create('App\PatronCategory')->id,
            'visited_at' => Carbon::now()->toDateTimeString(),
            'recorded_live' => true
        ];

        $this->post('/logbook/livecounter/add', [
            'patron_category_id' => $entry['patron_category_id']
        ]);

        $this->assertDatabaseHas('logbook_entries', $entry);
    }

    /** @test */
    public function it_deletes_the_last_record_from_the_database()
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

    /** @test */
    public function it_cannot_subtract_yesterdays_records()
    {
        $this->signIn();

        $entry = create('App\LogbookEntry', [
            'visited_at' => Carbon::now()->subDay()
        ]);

        $this->post('/logbook/livecounter/subtract', [
            'patron_category_id' => $entry->patron_category_id
        ]);

        $this->assertDatabaseHas('logbook_entries', $entry->toArray());
    }

    /** @test */
    public function it_rejects_an_invalid_patron_category_id()
    {
        $this->signIn()->withExceptionHandling();

        create('App\PatronCategory', ['id' => 1]);

        $this->post('/logbook/livecounter/add', [
            'patron_category_id' => 6
        ])->assertSessionHasErrors('patron_category_id');

        $this->post('/logbook/livecounter/remove', [
            'patron_category_id' => 6
        ])->assertSessionHasErrors('patron_category_id');
    }

    /** @test */
    public function it_returns_a_collection_of_visits_keyed_with_patron_category_ids()
    {
        $this->signIn();

        $patronCategories = create('App\PatronCategory', [], 3);

        $entry1 = create('App\LogbookEntry', [
            'patron_category_id' => $patronCategories[0]->id
        ]);

        $entry2 = create('App\LogbookEntry', [
            'patron_category_id' => $patronCategories[1]->id
        ]);

        $response = $this->json('GET', '/logbook/livecounter/show');

        $response
            ->assertStatus(200)
            ->assertJson([
                1 => 1,
                2 => 1,
                3 => 0
            ]);
    }
}
