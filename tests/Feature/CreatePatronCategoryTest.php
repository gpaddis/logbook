<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreatePatronCategoryTest extends TestCase
{
    use DatabaseMigrations;

    // TODO: implement this and the following test to only allow the admin to add a patron category
    // /** @test */
    // public function normal_users_may_not_create_patron_categories()
    // {
    //     $this->expectException('Illuminate\Auth\AuthenticationException');
    //     $patronCategory = factory('App\PatronCategory')->make();

    //     $this->post('/settings/patron-categories', $patronCategory->toArray());
    // }

    /** @test */
    public function an_authenticated_user_can_create_new_patron_category()
    {
        $this->signIn();

        $patronCategory = make('App\PatronCategory');

        // The post() method needs an array as a second argument!
        $this->post('/patron-categories', $patronCategory->toArray());

        $this->get('/patron-categories')
            ->assertSee($patronCategory->name)
            ->assertSee($patronCategory->abbreviation);
    }
}