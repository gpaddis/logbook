<?php

use App\PatronCategory;
use Illuminate\Database\Seeder;

class LogbookEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        list($user1, $user2, $user3) = factory(PatronCategory::class, 3)->create();

        for ($year = 2010; $year < 2012 ; $year++) {
            for ($month = 1; $month < 13; $month++) {
                for ($hour = 12; $hour < 14; $hour++) {
                    create('App\LogbookEntry', [
                        'patron_category_id' => $user1->id,
                        'visited_at' => "{$year}-{$month}-13 {$hour}:12:00"
                        ], 5);

                    create('App\LogbookEntry', [
                        'patron_category_id' => $user2->id,
                        'visited_at' => "{$year}-{$month}-13 {$hour}:13:00"
                        ], 4);

                    create('App\LogbookEntry', [
                        'patron_category_id' => $user3->id,
                        'visited_at' => "{$year}-{$month}-13 {$hour}:05:00"
                        ], 5);
                }
            }
        }
    }
}
