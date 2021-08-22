<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // other classes morphmap generated via plugin
        // spatie/laravel-morph-map-generator
        Relation::morphMap([
            'user' => 'App\Models\Post',
            'role' => 'App\Models\Video',
        ]);
    }
}
