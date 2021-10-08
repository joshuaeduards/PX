<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\JWTService;

class JWTServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(JWTService::class, function ($app) {
            return new JWTService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
