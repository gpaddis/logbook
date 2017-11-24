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
    public function it_gets_the_visits_by_year_grouped_by_month()
    {
        $this->signIn();

        $response = $this->json('GET', '/api/visits/2010');
        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'visits' => [
                    '1' => '28',
                    '2' => '28'
                ],
                'period' => [
                    'year' => 2010
                ],
                'groupedBy' => 'month'
            ]
        ]);
    }

    /** @test */
    public function it_gets_the_visits_by_month_grouped_by_day()
    {
        $this->signIn();

        $response = $this->json('GET', '/api/visits/2010/02');
        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'visits' => [
                    '13' => '28'
                ],
                'period' => [
                    'year' => 2010,
                    'month' => 02
                ],
                'groupedBy' => 'day'
            ]
        ]);
    }

    /** @test */
    public function it_gets_the_visits_by_day_grouped_by_hour()
    {
        $this->signIn();

        $response = $this->json('GET', '/api/visits/2010/02/13');
        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'visits' => [
                    '12' => '14',
                    '13' => '14',
                ],
                'period' => [
                    'year' => 2010,
                    'month' => 02,
                    'day' => 13
                ],
                'groupedBy' => 'hour'
            ]
        ]);
    }
}
