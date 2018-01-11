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

    /**
     * Convert the JSON response to an array.
     *
     * @param [type] $response
     * @return array
     */
    protected function jsonToArray($response)
    {
        return json_decode(json_encode($response->getData()), true);
    }

    /** @test */
    public function it_gets_the_visits_by_year_grouped_by_month()
    {
        $response = $this->json('GET', '/api/visits/2010');
        $response->assertStatus(200)
        ->assertJson([
            'data' => [
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
                'label' => 'February 2010',
                'period' => [
                    'year' => 2010,
                    'month' => 02
                ],
                'groupedBy' => 'day'
            ]
        ]);

        $visits = $this->jsonToArray($response)['data']['visits']['13'];
        $this->assertEquals(28, $visits);
    }

    /** @test */
    public function it_gets_the_visits_by_day_grouped_by_hour()
    {
        $response = $this->json('GET', '/api/visits/2010/02/13');
        $response->assertStatus(200)
        ->assertJson([
            'data' => [
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

    /** @test */
    public function it_allows_for_custom_groupings()
    {
        $this->withExceptionHandling();

        $this->json('GET', '/api/visits/2010?groupBy=month')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'groupedBy' => 'month'
            ]
        ]);

        $this->json('GET', '/api/visits/2010?groupBy=day')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'groupedBy' => 'day'
            ]
        ]);

        $this->json('GET', '/api/visits/2010?groupBy=hour')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'groupedBy' => 'hour'
            ]
        ]);

        // It validates the request
        $this->json('GET', '/api/visits/2010?groupBy=minute')
        ->assertStatus(422);
    }
}
