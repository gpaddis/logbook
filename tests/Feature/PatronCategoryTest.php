<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\PatronCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatronCategoryTest extends TestCase
{
    use RefreshDatabase;

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
    public function a_newly_created_patron_category_is_primary_by_default()
    {
        $category = PatronCategory::first();

        $this->assertEquals(true, $category->is_primary);
    }

    /** @test */
    public function a_category_is_visible_on_the_patron_categories_page()
    {
        $this->signIn();

        $this->get('/patron-categories')
            ->assertSee($this->category->name)
            ->assertSee('Primary')
            ->assertSee('Active');
    }

    /** @test */
    public function an_inactive_patron_category_name_is_visible_on_the_patron_categories_page()
    {
        $this->signIn();

        $inactiveCategory = create('App\PatronCategory', ['is_active' => false]);

        $this->get('/patron-categories')
            ->assertSee($inactiveCategory->name);
    }

    /** @test */
    public function an_active_category_name_is_visible_on_the_update_logbook_entry_page()
    {
        $this->signIn();

        $this->get('/logbook/update')
            ->assertSee($this->category->name);
    }

    /** @test */
    public function an_inactive_category_name_is_not_visible_on_the_update_logbook_entry_page()
    {
        $this->signIn();

        $patronCategory = create('App\PatronCategory', ['is_active' => false]);

        $this->get('/logbook/update')
            ->assertDontSee($patronCategory->name);
    }

    /** @test */
    public function it_shows_all_non_boolean_fields_on_the_category_page()
    {
        $this->signIn();
        $category = create('App\PatronCategory');

        $this->get($category->path())
            ->assertSee($category->name)
            ->assertSee($category->abbreviation)
            ->assertSee($category->notes)
            ->assertSee($category->created_at->diffForHumans())
            ->assertSee($category->updated_at->diffForHumans());
    }
}
