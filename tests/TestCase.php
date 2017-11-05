<?php

namespace Tests;

use App\User;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        Artisan::call('db:seed');

        $this->disableExceptionHandling();
    }

    /**
     * Sign in a user easily for test purposes. Let it be an admin for now, so that we
     * can specify if we want another user role instead.
     *
     * @param  App\User $user
     */
    protected function signIn(User $user = null)
    {
        $user = $user ?: create('App\User')->assignRole('admin');

        $this->actingAs($user);

        return $this;
    }

    /**
     * This and the following methods have been stolen from
     * https://gist.github.com/adamwathan/125847c7e3f16b88fa33a9f8b42333da
     */
    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }
            public function report(\Exception $e)
            {
            }
            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
        return $this;
    }
}
