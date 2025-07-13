<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
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
        // Check if locale is set in the URL
        if ($request->has('lang')) {
            $locale = $request->lang;
            Session::put('locale', $locale);
            
            // Set RTL direction for Arabic
            if ($locale === 'ar') {
                Session::put('isRTL', true);
            } else {
                Session::forget('isRTL');
            }
        }
        
        // Get locale from session or default to English
        $locale = Session::get('locale', config('app.locale', 'en'));
        App::setLocale($locale);
        
        // Share variables with all views
        view()->share('currentLocale', $locale);
        view()->share('isRTL', Session::get('isRTL', false));
        
        return $next($request);
    }
} 