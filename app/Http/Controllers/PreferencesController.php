<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class PreferencesController extends Controller
{
    /**
     * Update the user's language preference.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function language(Request $request)
    {
        $language = $request->language ?? 'en';
        
        if (in_array($language, ['en', 'ar'])) {
            Session::put('locale', $language);
            App::setLocale($language);
            
            // Set RTL direction for Arabic
            if ($language === 'ar') {
                Session::put('isRTL', true);
            } else {
                Session::forget('isRTL');
            }
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect($request->redirect ?? back()->getTargetUrl());
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
        
        if (in_array($theme, ['light', 'dark'])) {
            Session::put('theme_mode', $theme);
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect($request->redirect ?? back()->getTargetUrl());
    }
    
    /**
     * Toggle between light and dark theme.
     *
     * @return \Illuminate\Http\Response
     */
    public function themeToggle()
    {
        $currentTheme = Session::get('theme_mode', 'dark');
        $newTheme = ($currentTheme === 'dark') ? 'light' : 'dark';
        
        Session::put('theme_mode', $newTheme);
        
        return back();
    }
    
    /**
     * Update the user's currency preference.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function currency(Request $request)
    {
        $currencyCode = $request->currency_code ?? 'EGP';
        
        // Validate if currency exists and is active
        $currency = \App\Models\Currency::where('code', $currencyCode)
            ->where('is_active', true)
            ->first();
            
        if ($currency) {
            Session::put('currency_code', $currencyCode);
            Session::put('currency_symbol', $currency->symbol);
            Session::put('currency_rate', $currency->exchange_rate);
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect($request->redirect ?? back()->getTargetUrl());
    }
} 