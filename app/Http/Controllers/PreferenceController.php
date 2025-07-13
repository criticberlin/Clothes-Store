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
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'theme' => $newTheme]);
        }
        
        return redirect()->back();
    }
    
    /**
     * Set theme to specific value
     */
    public function setTheme(Request $request)
    {
        $theme = $request->theme;
        
        if (in_array($theme, ['light', 'dark'])) {
            Session::put('theme_mode', $theme);
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'theme' => $theme]);
        }
        
        if ($request->has('redirect')) {
            return redirect($request->redirect);
        }
        
        return redirect()->back();
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
        
        $currency = Currency::where('code', $currencyCode)->where('is_active', true)->first();
        
        if ($currency) {
            Session::put('currency_code', $currencyCode);
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'currency' => $currencyCode,
                'symbol' => $currency ? $currency->symbol : null
            ]);
        }
        
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
