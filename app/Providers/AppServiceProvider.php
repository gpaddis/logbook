<?php

namespace App\Providers;

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
        \View::composer([
                'logbook.create',
                'logbook.livecounter.index'
            ], function($view) {
            $view->with('active_patron_categories', \App\PatronCategory::active()->get());
            $view->with('primary_active_patron_categories', \App\PatronCategory::active()->primary()->get());
            $view->with('secondary_active_patron_categories', \App\PatronCategory::active()->secondary()->get());
        });
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
