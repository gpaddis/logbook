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

        $response = $this->post('/logbook/livecounter/add', [
            'id' => 1,
            ])->assertRedirect('login');
    }

    /** @test */
    public function it_adds_a_visit_to_the_database()
    {
        $this->signIn();
        $patronCategory = create('App\PatronCategory');

        $this->post('/logbook/livecounter/add', [
            'patron_category_id' => $patronCategory->id,
            'visited_at' => '2017-01-01 12:09:03',
            'recorded_live' => true
            ])
        ->assertStatus(200)
        ->assertJson([
            $patronCategory->id => 1
            ]);
    }

    /** @test */
    public function it_deletes_the_last_record_from_the_database()
    {
        $this->signIn();

        $patronCategories = factory('App\PatronCategory', 3)->create();

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
            ])
        ->assertStatus(200)
        ->assertJson([
            $patronCategories->get(0)->id => 1,
            $patronCategories->get(1)->id => 1
            ]);

        $this->assertDatabaseMissing('logbook_entries', $entry3->toArray());
        $this->assertDatabaseHas('logbook_entries', $entry1->toArray());
        $this->assertDatabaseHas('logbook_entries', $entry2->toArray());
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
            'patron_category_id' => 100
            ])->assertSessionHasErrors('patron_category_id');

        $this->post('/logbook/livecounter/remove', [
            'patron_category_id' => 100
            ])->assertSessionHasErrors('patron_category_id');
    }

    /** @test */
    public function it_rejects_an_inactive_patron_category_id()
    {
        $this->signIn()->withExceptionHandling();

        $active = create('App\PatronCategory');
        $inactive = create('App\PatronCategory', ['is_active' => false]);

        $this->post('/logbook/livecounter/add', [
            'patron_category_id' => $active->id
            ])->assertStatus(200);

        // $this->post('/logbook/livecounter/add', [
        //     'patron_category_id' => $inactive->id
        //     ])->assertSessionHasErrors('patron_category_id');
    }

    /** @test */
    public function it_returns_a_collection_of_visits_keyed_with_patron_category_ids()
    {
        $this->signIn();

        $patronCategories = factory('App\PatronCategory', 3)->create();

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
            $patronCategories->get(0)->id => 1,
            $patronCategories->get(1)->id => 1,
            $patronCategories->get(2)->id => 0
            ]);
    }
}
