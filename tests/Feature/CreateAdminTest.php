<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_the_registration_route_only_if_there_are_no_admins_in_the_database()
    {
        $this->withExceptionHandling();
        $this->get('/register-admin')->assertStatus(200);

        create('App\User')->assignRole('admin');

        $this->get('/register-admin')->assertStatus(403);
        $this->post('/register-admin', [])->assertStatus(403);
    }
}
