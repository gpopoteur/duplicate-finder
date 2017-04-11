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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Contracts\DuplicateFinder::class, 
            \App\Finders\DuplicateFinder::class
        );

        // Repositories
        $this->app->bind(
            \App\Contracts\CustomerRepository::class, 
            \App\Repositories\EloquentCustomerRepository::class
        );
    }
}
