<?php

namespace Tests\Feature\Browse;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YearTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_year_requested_must_be_present_in_the_database()
    {
        $this->signIn()->withExceptionHandling();

        create('App\LogbookEntry', [
            'visited_at' => '2015-04-05 12:00:09'
        ], 6);

        $this->get('/logbook/year?y=2017')
        ->assertSessionHasErrors('y');
    }
}
