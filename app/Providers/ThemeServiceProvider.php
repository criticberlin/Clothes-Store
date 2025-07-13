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
        try {
            // Get theme from cookie, session, or default to dark
            $theme = $this->getValidTheme();
            
            // Set default language if not set - only allow valid locales
            $locale = $this->getValidLocale();
            
            // Set default currency if not set
            $currencyCode = $this->getValidCurrency();
            
            // Share theme, language and currency with all views
            View::composer('*', function ($view) use ($theme, $locale, $currencyCode) {
                $view->with([
                    'currentTheme' => $theme,
                    'currentLocale' => $locale,
                    'currentCurrency' => $currencyCode,
                    'isRTL' => in_array($locale, ['ar'])
                ]);
            });
        } catch (Exception $e) {
            // Log error but don't crash the application
            logger()->error('Theme service provider error: ' . $e->getMessage());
            
            // Set safe defaults
            Session::put('theme_mode', 'dark');
            Session::put('theme', 'dark');
            App::setLocale('en');
            Session::put('locale', 'en');
            Session::forget('isRTL');
        }
    }
    
    /**
     * Get a valid theme value
     * 
     * @return string
     */
    private function getValidTheme()
    {
        // Check all possible sources for theme preference
        $theme = null;
        
        // 1. Check cookie first (highest priority)
        if (request()->hasCookie('theme')) {
            $theme = request()->cookie('theme');
        }
        
        // 2. Check session variables
        if (!$theme || !in_array($theme, ['light', 'dark'])) {
            $theme = Session::get('theme');
        }
        
        if (!$theme || !in_array($theme, ['light', 'dark'])) {
            $theme = Session::get('theme_mode');
        }
        
        // 3. Default to dark if no valid theme found
        if (!$theme || !in_array($theme, ['light', 'dark'])) {
            $theme = 'dark';
        }
        
        // Ensure theme is set in both session variables for consistency
        Session::put('theme_mode', $theme);
        Session::put('theme', $theme);
        
        return $theme;
    }
    
    /**
     * Get a valid locale value
     * 
     * @return string
     */
    private function getValidLocale()
    {
        // Check all possible sources for locale preference
        $locale = null;
        
        // 1. Check cookie first (highest priority)
        if (request()->hasCookie('locale')) {
            $locale = request()->cookie('locale');
        }
        
        // 2. Check session
        if (!$locale || !in_array($locale, ['en', 'ar'])) {
            $locale = Session::get('locale');
        }
        
        // 3. Default to config or 'en'
        if (!$locale || !in_array($locale, ['en', 'ar'])) {
            $locale = config('app.locale', 'en');
        }
        
        // Ensure locale is valid
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        
        // Set locale in session and application
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Set RTL direction for Arabic
        if ($locale === 'ar') {
            Session::put('isRTL', true);
        } else {
            Session::forget('isRTL');
        }
        
        return $locale;
    }
    
    /**
     * Get a valid currency code
     *
     * @return string
     */
    private function getValidCurrency()
    {
        $currencyCode = null;
        
        // 1. Check cookie first (highest priority)
        if (request()->hasCookie('currency')) {
            $currencyCode = request()->cookie('currency');
        }
        
        // 2. Check session
        if (!$currencyCode) {
            $currencyCode = Session::get('currency_code');
        }
        
        if (!$currencyCode) {
            // Check if the currencies table exists
            try {
                if (Schema::hasTable('currencies')) {
                    $defaultCurrency = Currency::where('is_default', true)->first();
                    if ($defaultCurrency) {
                        $currencyCode = $defaultCurrency->code;
                        Session::put('currency_code', $defaultCurrency->code);
                        Session::put('currency_symbol', $defaultCurrency->symbol);
                        Session::put('currency_rate', $defaultCurrency->exchange_rate);
                    } else {
                        $currencyCode = 'EGP';
                        Session::put('currency_code', 'EGP');
                        Session::put('currency_symbol', 'ج.م');
                        Session::put('currency_rate', 1);
                    }
                } else {
                    // Default to EGP if table doesn't exist
                    $currencyCode = 'EGP';
                    Session::put('currency_code', 'EGP');
                    Session::put('currency_symbol', 'ج.م');
                    Session::put('currency_rate', 1);
                }
            } catch (Exception $e) {
                // Default to EGP in case of any errors
                $currencyCode = 'EGP';
                Session::put('currency_code', 'EGP');
                Session::put('currency_symbol', 'ج.م');
                Session::put('currency_rate', 1);
            }
        }
        
        return $currencyCode;
    }
}
