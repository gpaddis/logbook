<?php

namespace Tests\Feature\Browse;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YearTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_tab_no_matter_what()
    {
        $this->signIn();

        $this->get('/logbook/year')
        ->assertStatus(200)
        ->assertSee(auth()->user()->first_name);
    }

    /** @test */
    public function the_year_requested_must_be_present_in_the_database()
    {
        $this->signIn()->withExceptionHandling();

        create('App\LogbookEntry', [
            'visited_at' => '2015-04-05 12:00:09'
        ], 6);

        $this->get('/logbook/year?y1=2017')
        ->assertSessionHasErrors('y1');

        $this->get('/logbook/year?y1=2015')
        ->assertStatus(200);
    }
}
