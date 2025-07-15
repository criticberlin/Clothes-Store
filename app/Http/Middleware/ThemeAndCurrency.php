<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Currency;
use App\Services\CurrencyService;

class ThemeAndCurrency
{
    /**
     * The currency service.
     *
     * @var \App\Services\CurrencyService
     */
    protected $currencyService;
    
    /**
     * Constructor.
     *
     * @param \App\Services\CurrencyService $currencyService
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Handle theme - check cookie first, then session
        $theme = $request->cookie('theme') ?? Session::get('theme', Session::get('theme_mode', 'dark'));
        
        // If theme comes from cookie, update session for consistency
        if ($request->cookie('theme')) {
            Session::put('theme_mode', $request->cookie('theme'));
            Session::put('theme', $request->cookie('theme'));
        }
        
        // Share theme with all views
        view()->share('currentTheme', $theme);
        
        // Get current currency using the service
        $currentCurrency = $this->currencyService->getCurrentCurrency();
        
        // Get all active currencies
        $currencies = $this->currencyService->getActiveCurrencies();
        
        // Share currency information with all views
        view()->share('currentCurrency', $currentCurrency);
        view()->share('currencies', $currencies);
        
        return $next($request);
    }
} 