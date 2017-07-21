<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Counters\PatronCategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PatronCategoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_newly_created_patron_category_is_active_by_default()
    {
        factory('App\Counters\PatronCategory')->create();
        
        // Using the instance just created is not enough: you have to retrieve
        // the instance in the database to check if it is active
        $category = PatronCategory::first();

        $this->assertEquals(true, $category->is_active);
    }
}
