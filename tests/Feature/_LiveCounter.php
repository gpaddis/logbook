<?php

namespace Tests\Feature;

use Timeslot\Timeslot;
use Tests\TestCase;
use App\Logbook\Entry;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LiveCounterTest extends TestCase
{
    use DatabaseMigrations;

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
            ->assertSee((string) $entry->visits);
    }

    /** @test */
    public function it_can_record_a_visit_if_it_does_not_yet_exist()
    {
        $timeslot = Timeslot::now();
        $patron_category_id = create('App\PatronCategory')->id;

        Entry::add($patron_category_id, $timeslot);

        $this->assertEquals(1, Entry::first()->visits);
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

        $this->assertEquals($existingEntry->visits + 1, Entry::first()->visits);
    }

    /** @test */
    public function it_subtracts_a_visit_from_the_count()
    {
        $timeslot = Timeslot::now();
        $existingEntry = create('App\Logbook\Entry', [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end(),
            'visits' => 6
            ]);

        Entry::subtract($existingEntry->patron_category_id, $timeslot);

        $this->assertEquals($existingEntry->visits - 1, Entry::first()->visits);
    }

    /** @test */
    public function it_deletes_the_entry_if_count_is_equal_to_1()
    {
        $timeslot = Timeslot::now();
        $existingEntry = create('App\Logbook\Entry', [
            'start_time' => $timeslot->start(),
            'end_time' => $timeslot->end(),
            'visits' => 1
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
            'visits' => 0
            ]);

        Entry::subtract($existingEntry->patron_category_id, $timeslot);

        $this->assertEquals(null, Entry::first());
    }

    /** @test */
    public function it_does_nothing_if_theres_no_corresponding_entry()
    {
        $this->assertEquals(null, Entry::subtract(1, Timeslot::now()));
    }

    /** @test */
    public function it_displays_the_toggle_secondary_categories_link_if_there_are_some()
    {
        $this->signIn();

        create('App\PatronCategory', ['is_primary' => true], 3);
        create('App\PatronCategory', ['is_primary' => false], 3);

        $this->get('/logbook/livecounter')
            ->assertSee('Toggle secondary categories...');
    }

    /** @test */
    public function it_hides_the_toggle_secondary_categories_link_if_there_are_none()
    {
        $this->signIn();

        create('App\PatronCategory', ['is_primary' => true], 3);

        $this->get('/logbook/livecounter')
            ->assertDontSee('Toggle secondary categories...');
    }
}
