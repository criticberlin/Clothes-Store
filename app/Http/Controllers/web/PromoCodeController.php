<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PromoCodeController extends Controller
{
    /**
     * Apply a promo code to the checkout session.
     */
    public function apply(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'code' => 'required|string|max:50',
        ]);
        
        // Check if promo code exists and is valid
        $promoCode = PromoCode::where('code', $request->code)->active()->first();
        
        if (!$promoCode) {
            return redirect()->back()->with('error', 'Invalid or expired promo code.');
        }
        
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data', []);
        
        // Update promo code
        $checkoutData['promo_code'] = $request->code;
        
        // Save to session
        Session::put('checkout_data', $checkoutData);
        
        return redirect()->back()->with('success', 'Promo code applied successfully.');
    }
    
    /**
     * Remove a promo code from the checkout session.
     */
    public function remove()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data', []);
        
        // Remove promo code
        $checkoutData['promo_code'] = null;
        
        // Save to session
        Session::put('checkout_data', $checkoutData);
        
        return redirect()->back()->with('success', 'Promo code removed.');
    }
    
    /**
     * Validate a promo code.
     */
    public function validateCode(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['valid' => false, 'message' => 'Unauthorized'], 401);
        }
        
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);
        
        // Check if promo code exists and is valid
        $promoCode = PromoCode::where('code', $request->code)->active()->first();
        
        if (!$promoCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired promo code.',
            ]);
        }
        
        // Calculate discount
        $discount = $promoCode->calculateDiscount($request->subtotal);
        
        if ($discount <= 0) {
            $message = 'This promo code requires a minimum order amount of ' . 
                number_format($promoCode->min_order_amount, 2) . ' ' . config('app.currency');
            
            return response()->json([
                'valid' => false,
                'message' => $message,
            ]);
        }
        
        return response()->json([
            'valid' => true,
            'message' => 'Promo code applied successfully.',
            'discount' => $discount,
            'discount_formatted' => number_format($discount, 2) . ' ' . config('app.currency'),
            'type' => $promoCode->type,
            'value' => $promoCode->value,
        ]);
    }
}
