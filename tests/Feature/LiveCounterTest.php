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
        $this->assertEquals(1, $entry->visits_count);
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
    public function it_rejects_an_invalid_operation()
    {
        $this->signIn()->withExceptionHandling();

        create('App\PatronCategory', ['id' => 1]);

        $this->get('/logbook/livecounter/store?id=1&operation=deleteallrecords')
        ->assertSessionHasErrors('operation');
    }

    /** @test */
    public function it_rejects_an_invalid_patron_category_id()
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

    /** @test */
    public function the_current_count_is_visible_on_the_livecounter_index_page()
    {
        $this->signIn();

        $timeslot = Timeslot::now();
        $entry = create('App\Logbook\Entry', [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end(),
            ]);

        $this->get('logbook/livecounter')
        ->assertSee((string) $entry->visits_count);
    }

    /** @test */
    public function it_can_record_a_visit_if_it_does_not_yet_exist()
    {
        $timeslot = Timeslot::now();
        $patron_category_id = create('App\PatronCategory')->id;

        Entry::add($patron_category_id, $timeslot);

        $this->assertEquals(1, Entry::first()->visits_count);
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

        $this->assertEquals($existingEntry->visits_count + 1, Entry::first()->visits_count);
    }

    /** @test */
    public function it_subtracts_a_visit_from_the_count()
    {
        $timeslot = Timeslot::now();
        $existingEntry = create('App\Logbook\Entry', [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end(),
            'visits_count' => 6
            ]);

        Entry::subtract($existingEntry->patron_category_id, $timeslot);

        $this->assertEquals($existingEntry->visits_count - 1, Entry::first()->visits_count);
    }

    /** @test */
    public function it_deletes_the_entry_if_count_is_equal_to_1()
    {
        $timeslot = Timeslot::now();
        $existingEntry = create('App\Logbook\Entry', [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end(),
            'visits_count' => 1
            ]);

        Entry::subtract($existingEntry->patron_category_id, $timeslot);

        $this->assertEquals(null, Entry::first());
    }

    /** @test */
    public function it_deletes_the_entry_if_count_is_equal_to_0()
    {
        $timeslot = Timeslot::now();
        $existingEntry = create('App\Logbook\Entry', [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end(),
            'visits_count' => 0
            ]);

        Entry::subtract($existingEntry->patron_category_id, $timeslot);

        $this->assertEquals(null, Entry::first());
    }

    /** @test */
    public function it_does_nothing_if_theres_no_corresponding_entry()
    {
        $this->assertEquals(null, Entry::subtract(1, Timeslot::now()));
    }
}
