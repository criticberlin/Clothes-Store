<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PreferenceController extends Controller
{
    /**
     * The currency service instance.
     * 
     * @var \App\Services\CurrencyService
     */
    protected $currencyService;
    
    /**
     * Create a new controller instance.
     * 
     * @param \App\Services\CurrencyService $currencyService
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->middleware('auth:admin');
        $this->currencyService = $currencyService;
    }
    
    /**
     * Update the admin's currency preference.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateCurrency(Request $request)
    {
        $currencyCode = $request->currency_code ?? $request->currency;
        
        // Find the requested currency
        $currency = Currency::where('code', $currencyCode)
            ->where('is_active', true)
            ->first();
        
        if ($currency) {
            // Store in session (admin specific)
            Session::put('admin_currency_code', $currencyCode);
            
            // Create a cookie that lasts for a year
            $cookie = cookie('admin_currency', $currencyCode, 60 * 24 * 365);
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'currency' => $currencyCode,
                    'symbol' => $currency->getSymbolForCurrentLocale(),
                    'rate' => $currency->rate,
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
} 