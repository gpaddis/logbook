<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\PatronCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePatronCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unauthenticated_user_cannot_create_a_patron_category()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/patron-categories', []);
    }

    /** @test */
    public function a_normal_user_cannot_create_a_patron_category()
    {
        $this->withExceptionHandling();
        $user = create('App\User')->assignRole('standard');

        $this->signIn($user);
        $this->post('/patron-categories', [])
        ->assertStatus(302);
    }

    /** @test */
    public function a_normal_user_cannot_see_the_protected_routes()
    {
        $this->withExceptionHandling();
        $user = create('App\User')->assignRole('standard');

        $this->signIn($user);

        $this->get('/patron-categories/create')
        ->assertStatus(302);
    }

    /** @test */
    public function only_an_admin_can_create_a_new_patron_category()
    {
        $this->signIn();
        $patronCategory = make('App\PatronCategory');

        $this->post('/patron-categories', $patronCategory->toArray())
        ->assertStatus(302);

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
    public function a_new_category_is_active_and_primary_by_default()
    {
        $this->signIn();

        $this->post('/patron-categories', [
            'name' => 'Category name',
            'abbreviation' => 'Abbr.',
            ])
        ->assertStatus(302);

        $this->assertTrue(PatronCategory::first()->is_active);
        $this->assertTrue(PatronCategory::first()->is_primary);
    }

    /** @test */
    public function it_can_update_a_patron_category()
    {
        $this->signIn();

        $category = create('App\PatronCategory');

        $this->patch('/patron-categories/' . $category->id, ['name' => 'Students']);
        $this->assertDatabaseHas('patron_categories', ['name' => 'Students']);
    }

    /** @test */
    public function it_validates_an_update_request()
    {
        $this->signIn()->withExceptionHandling();

        $category = create('App\PatronCategory');

        $this->patch('/patron-categories/' . $category->id, [
            'name' => 'A string too long to be updated does never pass validation.'
            ])
        ->assertSessionHasErrors('name');
    }
}
