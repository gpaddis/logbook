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
