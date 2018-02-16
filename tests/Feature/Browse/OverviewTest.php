<?php

namespace Tests\Feature\Browse;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OverviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_average_for_the_current_week()
    {
        $this->signIn();
        create('App\LogbookEntry', [], 9);

        $this->get('/logbook')
            ->assertStatus(200)
            ->assertSee('box-generic-lg value="9.0"')
            ->assertSee('Last week was 0.0');
    }
}
