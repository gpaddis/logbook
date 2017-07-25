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

        $this->category = create('App\PatronCategory');
    }

    /** @test */
    public function a_newly_created_patron_category_is_active_by_default()
    {   
        // Using the instance created in the setUp() method is not enough: we have to retrieve
        // the instance persisted in the database to check if is_active = true.
        $category = PatronCategory::first();

        $this->assertEquals(true, $category->is_active);
    }

    /** @test */
    public function a_category_name_is_visible_on_its_settings_page()
    {
        $this->get('/settings/patron-categories')
            ->assertSee($this->category->name);
    }

    /** @test */
    function an_active_category_name_is_visible_on_the_logbook_page()
    {
        $this->get('/logbook')
            ->assertSee($this->category->name);
    }

    /** @test */
    function an_inactive_category_name_is_not_visible_on_the_logbook_page()
    {
        $patronCategory = create('App\PatronCategory', ['is_active' => false]);

        $this->get('/logbook')
            ->assertDontSee($patronCategory->name);
    }
}
