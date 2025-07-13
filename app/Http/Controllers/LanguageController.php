<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    /**
     * Valid locales supported by the application
     */
    protected $validLocales = ['en', 'ar'];
    
    /**
     * Switch the application language
     *
     * @param  string  $locale
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function switchLanguage($locale, Request $request)
    {
        // Validate locale
        if (!in_array($locale, $this->validLocales)) {
            $locale = 'en';
        }
        
        // Set session locale
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        // Set RTL direction for Arabic
        if ($locale === 'ar') {
            Session::put('isRTL', true);
        } else {
            Session::forget('isRTL');
        }
        
        // Create cookie that persists for a year
        $cookie = cookie('locale', $locale, 60 * 24 * 365);
        
        // Get redirect URL
        $redirect = $request->query('redirect', url()->previous());
        if ($redirect === url()->current()) {
            $redirect = url('/');
        }
        
        // Return response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'isRTL' => $locale === 'ar',
                'message' => 'Language changed successfully'
            ])->cookie($cookie);
        }
        
        return redirect($redirect)->cookie($cookie);
    }
} 