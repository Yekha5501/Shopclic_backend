<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request; // Use Symfony's Request class

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set the default string length for database schema
        Schema::defaultStringLength(191);

        // Force HTTPS in production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

       
    }
}
