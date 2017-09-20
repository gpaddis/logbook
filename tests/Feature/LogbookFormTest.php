<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\LogbookEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogbookFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_persists_several_logbook_entries_simultaneously()
    {
        $this->signIn();

        create('App\PatronCategory', [], 2);

        $entry1 = [
            'patron_category_id' => 1,
            'visited_at' => '2017-08-21 12:00:00',
            'visits' => 8
            ];

        $entry2 = [
            'patron_category_id' => 2,
            'visited_at' => '2017-08-21 13:00:00',
            'visits' => 2
            ];

        $this->post('/logbook', ['entry' => [
            'any_entry_id' => $entry1,
            'another_entry_id' => $entry2
            ]]);

        $this->assertDatabaseHas('logbook_entries', [
            'patron_category_id' => $entry1['patron_category_id']
            ]);
        $this->assertDatabaseHas('logbook_entries', [
            'patron_category_id' => $entry2['patron_category_id']
            ]);
        $this->assertCount(10, LogbookEntry::all());
    }
}
