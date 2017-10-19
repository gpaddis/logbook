<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\PatronCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

// use Illuminate\Foundation\Testing\WithoutMiddleware;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
// use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreatePatronCategoryTest extends TestCase
{
    use RefreshDatabase;

    // TODO: only an admin can create or edit a patron category

    /** @test */
    public function an_unauthenticated_user_cannot_create_a_patron_category()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/patron-categories', []);
    }

    /** @test */
    public function an_authenticated_user_can_create_a_new_patron_category()
    {
        $this->signIn();

        $patronCategory = make('App\PatronCategory');

        $this->post('/patron-categories', $patronCategory->toArray());

        $this->get('/patron-categories')
            ->assertSee($patronCategory->name)
            ->assertSee($patronCategory->abbreviation);
    }

    /** @test */
    public function the_name_cannot_be_longer_than_25_chars()
    {
        $this->signIn()->withExceptionHandling();

        $this->post('/patron-categories', create('App\PatronCategory', [
            'name' => 'Some random long string that will never pass validation'
            ])->toArray())
        ->assertSessionHasErrors('name');
    }

    /** @test */
    public function the_abbreviation_cannot_be_longer_than_10_chars()
    {
        $this->signIn()->withExceptionHandling();

        $this->post('/patron-categories', create('App\PatronCategory', [
            'abbreviation' => 'Some random long string that will never pass validation'
            ])->toArray())
        ->assertSessionHasErrors('abbreviation');
    }

    /** @test */
    public function it_is_active_and_primary_by_default()
    {
        $this->signIn();

        $this->post('/patron-categories', [
            'name' => 'Category name',
            'abbreviation' => 'Abbr.',
            ]);

        $this->assertTrue(PatronCategory::first()->is_active);
        $this->assertTrue(PatronCategory::first()->is_primary);
    }
}
