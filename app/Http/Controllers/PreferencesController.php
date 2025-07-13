<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class PreferencesController extends Controller
{
    /**
     * Valid locales supported by the application
     */
    protected $validLocales = ['en', 'ar'];
    
    /**
     * Valid themes supported by the application
     */
    protected $validThemes = ['light', 'dark'];
    
    /**
     * Update the user's language preference.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function language(Request $request)
    {
        $language = $request->language ?? 'en';
        
        // Validate the language
        if (!in_array($language, $this->validLocales)) {
            $language = 'en';
        }
        
        Session::put('locale', $language);
        App::setLocale($language);
        
        // Set language cookie that persists for a year
        $cookie = cookie('locale', $language, 60 * 24 * 365);
        
        // Set RTL direction for Arabic
        if ($language === 'ar') {
            Session::put('isRTL', true);
        } else {
            Session::forget('isRTL');
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'language' => $language])
                ->withCookie($cookie);
        }
        
        return redirect($request->redirect ?? back()->getTargetUrl())
            ->withCookie($cookie);
    }
    
    /**
     * Update the user's theme preference.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function theme(Request $request)
    {
        $theme = $request->theme ?? 'dark';
        
        // Validate the theme
        if (!in_array($theme, $this->validThemes)) {
            $theme = 'dark';
        }
        
        // Update both theme session variables for consistency
        Session::put('theme_mode', $theme);
        Session::put('theme', $theme);
        
        // Set theme cookie that persists for a year
        $cookie = cookie('theme', $theme, 60 * 24 * 365);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'theme' => $theme])
                ->cookie($cookie); // Use cookie() method to ensure cookie is set
        }
        
        return redirect($request->redirect ?? back()->getTargetUrl())
            ->cookie($cookie); // Use cookie() method to ensure cookie is set
    }
    
    /**
     * Toggle between light and dark theme.
     *
     * @return \Illuminate\Http\Response
     */
    public function themeToggle(Request $request)
    {
        // Use both theme session variables for consistency
        $currentTheme = Session::get('theme', Session::get('theme_mode', 'dark'));
        $newTheme = ($currentTheme === 'dark') ? 'light' : 'dark';
        
        // Update both theme session variables
        Session::put('theme_mode', $newTheme);
        Session::put('theme', $newTheme);
        
        // Set theme cookie that persists for a year
        $cookie = cookie('theme', $newTheme, 60 * 24 * 365);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'theme' => $newTheme])
                ->cookie($cookie); // Use cookie() method to ensure cookie is set
        }
        
        return back()->cookie($cookie); // Use cookie() method to ensure cookie is set
    }
    
    /**
     * Update the user's currency preference.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function currency(Request $request)
    {
        $currencyCode = $request->currency_code;
        
        if ($currencyCode) {
            Session::put('currency_code', $currencyCode);
            
            // Set currency cookie that persists for a year
            $cookie = cookie('currency', $currencyCode, 60 * 24 * 365);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'currency' => $currencyCode])
                    ->cookie($cookie);
            }
            
            return redirect($request->redirect ?? back()->getTargetUrl())
                ->cookie($cookie);
        }
        
        return redirect($request->redirect ?? back()->getTargetUrl());
    }

    /**
     * Simple direct language switcher.
     *
     * @param  string  $lang
     * @return \Illuminate\Http\Response
     */
    public function setLanguage($lang)
    {
        // Validate language
        if (!in_array($lang, $this->validLocales)) {
            $lang = 'en';
        }
        
        // Set session and app locale
        Session::put('locale', $lang);
        App::setLocale($lang);
        
        // Set RTL direction for Arabic
        if ($lang === 'ar') {
            Session::put('isRTL', true);
        } else {
            Session::forget('isRTL');
        }
        
        // Create cookie with a long expiration (1 year)
        $cookie = cookie('locale', $lang, 60 * 24 * 365);
        
        // Get redirect URL from query string or go back
        $redirect = request('redirect', url()->previous());
        
        // Avoid redirect loops
        if ($redirect === url()->current()) {
            $redirect = url('/');
        }
        
        // Redirect with cookie
        return redirect($redirect)->withCookie($cookie);
    }
    
    /**
     * Clear all preference cookies to help users recover from corrupted cookies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function clearPreferences(Request $request)
    {
        // Clear session variables
        Session::forget('theme');
        Session::forget('theme_mode');
        Session::forget('locale');
        Session::forget('isRTL');
        Session::forget('currency_code');
        Session::forget('currency_symbol');
        Session::forget('currency_rate');
        
        // Set default values
        Session::put('theme', 'dark');
        Session::put('theme_mode', 'dark');
        Session::put('locale', 'en');
        App::setLocale('en');
        
        // Clear cookies by setting them with a past expiration
        $themeCookie = cookie('theme', '', -1);
        $localeCookie = cookie('locale', '', -1);
        $currencyCookie = cookie('currency', '', -1);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Preferences cleared successfully'])
                ->cookie($themeCookie)
                ->cookie($localeCookie)
                ->cookie($currencyCookie);
        }
        
        return redirect($request->redirect ?? back()->getTargetUrl())
            ->cookie($themeCookie)
            ->cookie($localeCookie)
            ->cookie($currencyCookie)
            ->with('success', 'Preferences cleared successfully');
    }
} 