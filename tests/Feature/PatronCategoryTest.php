<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\PatronCategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PatronCategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->category = factory('App\PatronCategory')->create();
    }

    /** @test */
    public function a_newly_created_patron_category_is_active_by_default()
    {   
        // Using the instance created in the setUp() method is not enough: we have to retrieve
        // the instance in the database to check if it is active
        $category = PatronCategory::first();

        $this->assertEquals(true, $category->is_active);
    }

    /** @test */
    function a_category_name_is_visible_on_the_visitslog_page()
    {
        $this->get('/visits')
            ->assertSee($this->category->name);
    }

    /** @test */
    public function a_category_name_is_visible_on_its_settings_page()
    {
        $this->get('/settings/patron-categories')
            ->assertSee($this->category->name);
    }
}
