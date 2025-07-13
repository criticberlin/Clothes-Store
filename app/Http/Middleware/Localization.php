<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class Localization
{
    /**
     * Valid locales supported by the application
     */
    protected $validLocales = ['en', 'ar'];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Check if locale is set in the URL query parameter
            if ($request->has('lang')) {
                $locale = $request->lang;
                
                // Only set if it's a valid locale
                if (in_array($locale, $this->validLocales)) {
                    Session::put('locale', $locale);
                    
                    // Set RTL direction for Arabic
                    if ($locale === 'ar') {
                        Session::put('isRTL', true);
                    } else {
                        Session::forget('isRTL');
                    }
                    
                    // Set cookie for persistence
                    Cookie::queue('locale', $locale, 60 * 24 * 365);
                }
            }
            
            // Check URL path for direct language switching (e.g., /locale/ar)
            $path = $request->path();
            if (strpos($path, 'locale/') === 0) {
                $urlLocale = substr($path, 7); // Extract locale from path
                
                if (in_array($urlLocale, $this->validLocales)) {
                    Session::put('locale', $urlLocale);
                    
                    // Set RTL direction for Arabic
                    if ($urlLocale === 'ar') {
                        Session::put('isRTL', true);
                    } else {
                        Session::forget('isRTL');
                    }
                    
                    // Set cookie for persistence
                    Cookie::queue('locale', $urlLocale, 60 * 24 * 365);
                    
                    // Redirect to home or previous page
                    $redirect = $request->query('redirect', '/');
                    return redirect($redirect);
                }
            }
            
            // Get locale from session or default to English
            $locale = Session::get('locale');
            
            // Validate locale before setting
            if (!in_array($locale, $this->validLocales)) {
                $locale = config('app.locale', 'en');
                Session::put('locale', $locale);
            }
            
            App::setLocale($locale);
            
            // Share variables with all views
            view()->share('currentLocale', $locale);
            view()->share('isRTL', Session::get('isRTL', false));
            
            // Set cookie for persistence if not already set
            if (!$request->hasCookie('locale')) {
                Cookie::queue('locale', $locale, 60 * 24 * 365);
            }
        } catch (\Exception $e) {
            // Log error but don't crash the application
            logger()->error('Localization middleware error: ' . $e->getMessage());
            
            // Set safe defaults
            App::setLocale('en');
            Session::put('locale', 'en');
            Session::forget('isRTL');
            
            // Share variables with all views
            view()->share('currentLocale', 'en');
            view()->share('isRTL', false);
        }
        
        return $next($request);
    }
} 