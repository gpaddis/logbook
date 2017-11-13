<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_users_link_in_the_navbar_only_if_one_has_the_permission()
    {
        $usersLink = '>Users</a>';
        $notAllowedUser = create('App\User');
        $allowedUser = create('App\User')->givePermissionTo('manage users');

        $this->signIn($notAllowedUser);
        $this->get('/')->assertDontSee($usersLink);

        $this->signIn($allowedUser);
        $this->get('/')->assertSee($usersLink);
    }

    /** @test */
    public function it_displays_an_existing_user_on_the_index_page()
    {
        $this->signIn();

        create('App\User')->assignRole('admin');
        $user = User::with('roles')->first();

        $this->get('/users')
        ->assertSee($user->first_name)
        ->assertSee($user->last_name)
        ->assertSee($user->email)
        ->assertSee($user->roles->first()->name);
    }

    /** @test */
    public function an_admin_can_save_a_new_user_account()
    {
        $this->signIn();
        $user = [
            'first_name' => 'Testname',
            'last_name' => 'TestLastName',
            'email' => 'test@example.com',
            'password' => 'secret',
            'role' => 'standard',
        ];

        $this->post('/users', $user)
        ->assertStatus(302);

        $savedUser = User::whereEmail('test@example.com')->first();
        $this->assertTrue($savedUser->hasRole('standard'));
    }
}
