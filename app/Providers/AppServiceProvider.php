<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Fix MySQL < 5.7.7 release or MariaDB older than the 10.2.2 release issue:
         * SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long;
         * max key length is 767 bytes.
         *
         * @url https://laravel.com/docs/master/migrations#creating-indexes
         * @url https://laravel-news.com/laravel-5-4-key-too-long-error
         */
        Schema::defaultStringLength(191);

        // \View::composer([
        //         'logbook.livecounter.index'
        //     ], function($view) {
        //     $view->with('active_patron_categories', \App\PatronCategory::active()->get());
        //     $view->with('primary_active_patron_categories', \App\PatronCategory::active()->primary()->get());
        //     $view->with('secondary_active_patron_categories', \App\PatronCategory::active()->secondary()->get());
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
