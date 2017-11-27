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

        $this->signIn();

        Artisan::call('db:seed', ['--class' => 'LogbookEntrySeeder']);
    }

    /** @test */
    public function it_gets_the_visits_by_year_grouped_by_month()
    {
        $response = $this->json('GET', '/api/visits/2010');
        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'visits' => [
                    '1' => '28',
                    '2' => '28'
                ],
                'label' => 2010,
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
        $response = $this->json('GET', '/api/visits/2010/02');
        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'visits' => [
                    '13' => '28'
                ],
                'label' => 'February 2010',
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
        $response = $this->json('GET', '/api/visits/2010/02/13');
        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'visits' => [
                    '12' => '14',
                    '13' => '14',
                ],
                'label' => 'February 13, 2010',
                'period' => [
                    'year' => 2010,
                    'month' => 02,
                    'day' => 13
                ],
                'groupedBy' => 'hour'
            ]
        ]);
    }

    /** @test */
    public function it_rejects_malformed_requests()
    {
        $this->withExceptionHandling();

        $this->json('GET', '/api/visits/2010/02/499')
            ->assertStatus(422);

        $this->json('GET', '/api/visits/2010/900/12')
            ->assertStatus(422);

        $this->json('GET', '/api/visits/2010121/02/12')
            ->assertStatus(422);

        $this->json('GET', '/api/visits/abab/ab/ab')
            ->assertStatus(422);

        $this->json('GET', '/api/visits/2014/  /12')
            ->assertStatus(422);

        $this->json('GET', '/api/visits/2014/03/0')
            ->assertStatus(422);
    }
}
