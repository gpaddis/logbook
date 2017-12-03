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
}
