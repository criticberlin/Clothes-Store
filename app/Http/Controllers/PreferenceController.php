<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class PreferenceController extends Controller
{
    /**
     * Toggle theme between light and dark mode
     */
    public function toggleTheme(Request $request)
    {
        $currentTheme = Session::get('theme', 'dark');
        $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';
        
        Session::put('theme', $newTheme);
        Session::put('theme_mode', $newTheme); // Also update theme_mode for consistency
        
        // Set theme cookie that persists for a year
        $cookie = cookie('theme', $newTheme, 60 * 24 * 365);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'theme' => $newTheme])
                ->withCookie($cookie);
        }
        
        return redirect()->back()->withCookie($cookie);
    }
    
    /**
     * Set theme to specific value
     */
    public function setTheme(Request $request)
    {
        $theme = $request->theme;
        
        if (in_array($theme, ['light', 'dark'])) {
            Session::put('theme_mode', $theme);
            Session::put('theme', $theme); // Also update theme for consistency
            
            // Set theme cookie that persists for a year
            $cookie = cookie('theme', $theme, 60 * 24 * 365);
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            $response = response()->json(['success' => true, 'theme' => $theme]);
            
            if (isset($cookie)) {
                $response->withCookie($cookie);
            }
            
            return $response;
        }
        
        $redirect = $request->has('redirect') 
            ? redirect($request->redirect)
            : redirect()->back();
            
        if (isset($cookie)) {
            $redirect->withCookie($cookie);
        }
        
        return $redirect;
    }
    
    /**
     * Switch the application language
     */
    public function setLanguage(Request $request)
    {
        $locale = $request->language ?? $request->locale;
        
        if (in_array($locale, ['en', 'ar'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'language' => $locale]);
        }
        
        if ($request->has('redirect')) {
            return redirect($request->redirect);
        }
        
        return redirect()->back();
    }
    
    /**
     * Switch the application currency
     */
    public function setCurrency(Request $request)
    {
        $currencyCode = $request->currency_code ?? $request->currency;
        
        // Use the currency service to validate the currency
        $currencyService = app(\App\Services\CurrencyService::class);
        
        // Find the requested currency
        $currency = \App\Models\Currency::where('code', $currencyCode)
            ->where('is_active', true)
            ->first();
        
        if ($currency) {
            // Store in session
            Session::put('currency_code', $currencyCode);
            
            // Create a cookie that lasts for a year
            $cookie = cookie('currency', $currencyCode, 60 * 24 * 365);
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'currency' => $currencyCode,
                    'symbol' => $currency->getSymbolForCurrentLocale(),
                    'exchange_rate' => $currency->rate,
                    'is_default' => $currency->is_default
                ])->withCookie($cookie);
            }
            
            // Redirect with cookie for regular requests
            if ($request->has('redirect')) {
                return redirect($request->redirect)->withCookie($cookie);
            }
            
            return redirect()->back()->withCookie($cookie);
        }
        
        // Currency not found, return error response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Invalid currency']);
        }
        
        // Redirect without setting cookie
        if ($request->has('redirect')) {
            return redirect($request->redirect);
        }
        
        return redirect()->back();
    }
    
    /**
     * Get available currencies
     */
    public function getCurrencies()
    {
        $currencies = Currency::where('is_active', true)->get();
        
        return response()->json($currencies);
    }
}
