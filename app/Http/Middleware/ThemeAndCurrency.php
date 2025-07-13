<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Currency;

class ThemeAndCurrency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Handle theme
        $theme = Session::get('theme_mode', 'dark');
        view()->share('currentTheme', $theme);
        
        // Handle currency
        $currencyCode = Session::get('currency_code');
        
        if (!$currencyCode) {
            // Set default currency if not set
            $defaultCurrency = Currency::where('is_default', true)->first();
            
            if ($defaultCurrency) {
                $currencyCode = $defaultCurrency->code;
                Session::put('currency_code', $currencyCode);
                Session::put('currency_symbol', $defaultCurrency->symbol);
                Session::put('currency_rate', $defaultCurrency->exchange_rate);
            } else {
                // Fallback to EGP if no default currency is set
                $currencyCode = 'EGP';
                Session::put('currency_code', 'EGP');
                Session::put('currency_symbol', 'ج.م');
                Session::put('currency_rate', 1);
            }
        }
        
        // Get all active currencies for dropdown
        $currencies = Currency::where('is_active', true)->get();
        
        // Share variables with all views
        view()->share('currentCurrency', $currencyCode);
        view()->share('currencies', $currencies);
        
        return $next($request);
    }
} 