<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_exports_the_visits_in_csv()
    {
        $this->signIn();
        $entry = create('App\LogbookEntry');

        $response = $this->get('/logbook/export?from=2017-01-01&to=' . date('Y-m-d'));
        $response->assertStatus(200)->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertEquals(
            "start_time,category,visits\n\"".date('Y-m-d H:')."00:00\",".$entry->patronCategory->name.",1\n",
            $response->content()
        );
    }

    /** @test */
    public function it_redirects_back_if_there_are_no_visits()
    {
        $this->signIn();

        $this->get('/logbook/export?from=2017-01-01&to=' . date('Y-m-d'))
            ->assertRedirect();
    }
}
