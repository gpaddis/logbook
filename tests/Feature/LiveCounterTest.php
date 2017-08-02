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

        $this->get('/logbook/livecounter/store?id='. $patronCategory->id .'&operation=add');

        $entry = Entry::first();
        $timeslot = Timeslot::now();

        // Check whether the data was stored correctly
        $this->assertEquals($patronCategory->id, $entry->patron_category_id);
        $this->assertEquals(1, $entry->count);
        $this->assertEquals($timeslot->start(), $entry->start_time);
        $this->assertEquals($timeslot->end(), $entry->end_time);
    }

    /** @test */
    public function an_unauthenticated_user_may_not_submit_a_visit_count()
    {
        $this->withExceptionHandling();

        $this->get('/logbook/livecounter/store?id=1&operation=add')
            ->assertRedirect('login');
    }

    /** @test */
    public function it_may_not_submit_an_invalid_operation()
    {
        $this->signIn()->withExceptionHandling();

        create('App\PatronCategory', ['id' => 1]);

        $this->get('/logbook/livecounter/store?id=1&operation=deleteallrecords')
            ->assertSessionHasErrors('operation');
    }

    /** @test */
    public function it_may_not_submit_an_invalid_patron_category_id()
    {
        $this->signIn()->withExceptionHandling();

        create('App\PatronCategory', ['id' => 1]);

        $this->get('/logbook/livecounter/store?id=6&operation=add')
            ->assertSessionHasErrors('id');
    }

    /** @test */
    public function it_requires_all_arguments()
    {
        $this->signIn()->withExceptionHandling();

        create('App\PatronCategory', ['id' => 1]);

        $this->get('/logbook/livecounter/store?id=6')
            ->assertSessionHasErrors('operation');

        $this->get('/logbook/livecounter/store?operation=add')
            ->assertSessionHasErrors('id');

        $this->get('/logbook/livecounter/store')
            ->assertSessionHasErrors('id', 'operation');
    }
}
