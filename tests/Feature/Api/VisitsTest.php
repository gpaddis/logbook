<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VisitsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'LogbookEntrySeeder']);
    }

    /** @test */
    public function it_returns_the_visits_per_hour_on_a_specific_day()
    {
        $this->signIn();

        $response = $this->json('GET', '/api/visits/2010/02/13');
        $response->assertStatus(200)
        ->assertJson([ // TODO: structure the response more appropriately.
            '12' => '14',
            '13' => '14',
        ]);
    }
}
