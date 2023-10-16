<?php

namespace App\Providers;

use App\Services\PasswordGeneratorService;
use Illuminate\Support\ServiceProvider;

class GeneratePasswordProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('App\Services\Contracts\PasswordGeneratorContract', function ($app) {
            return new PasswordGeneratorService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
