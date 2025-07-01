<?php

namespace App\Providers;

use App\Models\Currency;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set default theme if not set
        if (!Session::has('theme_mode')) {
            Session::put('theme_mode', 'dark');
        }
        
        // Set default language if not set
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);
        
        // Set default currency if not set
        if (!Session::has('currency_code')) {
            // Check if the currencies table exists
            try {
                if (Schema::hasTable('currencies')) {
                    $defaultCurrency = Currency::where('is_default', true)->first();
                    if ($defaultCurrency) {
                        Session::put('currency_code', $defaultCurrency->code);
                    } else {
                        Session::put('currency_code', 'EGP');
                    }
                } else {
                    // Default to EGP if table doesn't exist
                    Session::put('currency_code', 'EGP');
                }
            } catch (Exception $e) {
                // Default to EGP in case of any errors
                Session::put('currency_code', 'EGP');
            }
        }
        
        // Share theme, language and currency with all views
        View::composer('*', function ($view) {
            $view->with([
                'currentTheme' => Session::get('theme_mode', 'dark'),
                'currentLocale' => App::getLocale(),
                'currentCurrency' => Session::get('currency_code', 'EGP'),
                'isRTL' => in_array(App::getLocale(), ['ar'])
            ]);
        });
    }
}
