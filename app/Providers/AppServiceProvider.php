<?php

namespace App\Providers;

use App\Services\CurrencyService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Manually include the helpers file
        require_once app_path('helpers.php');
        
        // Define a fake Socialite class if it doesn't exist
        if (!class_exists('Laravel\Socialite\Facades\Socialite')) {
            class_alias('Illuminate\Support\Facades\Auth', 'Laravel\Socialite\Facades\Socialite');
        }

        // Register the CurrencyService as a singleton
        $this->app->singleton(CurrencyService::class, function ($app) {
            return new CurrencyService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for MySQL
        Schema::defaultStringLength(191);
    }
}
