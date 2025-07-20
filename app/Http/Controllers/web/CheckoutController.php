<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\PromoCode;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to continue with checkout.');
        }
        
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
            ->with(['product', 'color', 'size'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Get user addresses
        $addresses = $user->addresses;
        
        // Get shipping methods
        $shippingMethods = ShippingMethod::active()->get();
        
        // Get payment methods
        $paymentMethods = PaymentMethod::active()->get();
        
        // Get checkout data from session with default values for all required keys
        $checkoutData = Session::get('checkout_data', []);
        
        // Ensure all required keys exist
        $checkoutData = array_merge([
            'address_id' => $user->defaultAddress?->id,
            'shipping_method_id' => null,
            'payment_method_id' => null,
            'promo_code' => null,
            'step' => 1,
        ], $checkoutData);
        
        // Save the merged data back to the session
        Session::put('checkout_data', $checkoutData);
        
        // Calculate totals
        $shippingCost = 0;
        $paymentFee = 0;
        $discount = 0;
        
        // Add shipping cost if shipping method is selected
        if (!empty($checkoutData['shipping_method_id'])) {
            $shippingMethod = ShippingMethod::find($checkoutData['shipping_method_id']);
            if ($shippingMethod) {
                $shippingCost = $shippingMethod->cost;
            }
        }
        
        // Add payment fee if payment method is selected
        if (!empty($checkoutData['payment_method_id'])) {
            $paymentMethod = PaymentMethod::find($checkoutData['payment_method_id']);
            if ($paymentMethod) {
                $paymentFee = $paymentMethod->fee;
            }
        }
        
        // Apply promo code if provided
        if (!empty($checkoutData['promo_code'])) {
            $promoCode = PromoCode::where('code', $checkoutData['promo_code'])->active()->first();
            if ($promoCode) {
                $discount = $promoCode->calculateDiscount($subtotal);
            }
        }
        
        // Calculate total
        $total = $subtotal + $shippingCost + $paymentFee - $discount;
        
        return view('checkout.index', compact(
            'cartItems',
            'addresses',
            'shippingMethods',
            'paymentMethods',
            'checkoutData',
            'subtotal',
            'shippingCost',
            'paymentFee',
            'discount',
            'total'
        ));
    }
    
    /**
     * Save the address step.
     */
    public function saveAddress(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'address_id' => 'required|exists:addresses,id,user_id,' . Auth::id(),
        ]);
        
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data', []);
        
        // Update address
        $checkoutData['address_id'] = $request->address_id;
        $checkoutData['step'] = 2;
        
        // Save to session
        Session::put('checkout_data', $checkoutData);
        
        return redirect()->route('checkout.index');
    }
    
    /**
     * Save the shipping method step.
     */
    public function saveShipping(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'shipping_method_id' => 'required|exists:shipping_methods,id',
        ]);
        
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data', []);
        
        // Update shipping method
        $checkoutData['shipping_method_id'] = $request->shipping_method_id;
        $checkoutData['step'] = 3;
        
        // Save to session
        Session::put('checkout_data', $checkoutData);
        
        return redirect()->route('checkout.index');
    }
    
    /**
     * Save the payment method step.
     */
    public function savePayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
        
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data', []);
        
        // Update payment method
        $checkoutData['payment_method_id'] = $request->payment_method_id;
        $checkoutData['step'] = 4;
        
        // Save to session
        Session::put('checkout_data', $checkoutData);
        
        return redirect()->route('checkout.index');
    }
    
    /**
     * Apply a promo code.
     */
    public function applyPromoCode(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'promo_code' => 'required|string',
        ]);
        
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data', []);
        
        // Check if promo code exists and is valid
        $promoCode = PromoCode::where('code', $request->promo_code)->active()->first();
        
        if (!$promoCode) {
            return redirect()->back()->with('error', 'Invalid or expired promo code.');
        }
        
        // Update promo code
        $checkoutData['promo_code'] = $request->promo_code;
        
        // Save to session
        Session::put('checkout_data', $checkoutData);
        
        return redirect()->route('checkout.index')->with('success', 'Promo code applied successfully.');
    }
    
    /**
     * Remove a promo code.
     */
    public function removePromoCode()
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
        
        return redirect()->route('checkout.index')->with('success', 'Promo code removed.');
    }
    
    /**
     * Process the checkout.
     */
    public function process(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'terms_accepted' => 'required|accepted',
        ]);
        
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data', []);
        
        // Validate checkout data
        if (empty($checkoutData['address_id']) || 
            empty($checkoutData['shipping_method_id']) || 
            empty($checkoutData['payment_method_id'])) {
            return redirect()->route('checkout.index')
                ->with('error', 'Please complete all checkout steps before placing your order.');
        }
        
        // Get user and cart items
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
            ->with(['product', 'color', 'size'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        // Get address, shipping method, and payment method
        $address = Address::find($checkoutData['address_id']);
        $shippingMethod = ShippingMethod::find($checkoutData['shipping_method_id']);
        $paymentMethod = PaymentMethod::find($checkoutData['payment_method_id']);
        
        // Check if they exist
        if (!$address || !$shippingMethod || !$paymentMethod) {
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid checkout data. Please try again.');
        }
        
        // Calculate subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        // Get shipping cost
        $shippingCost = $shippingMethod->cost;
        
        // Get payment fee
        $paymentFee = $paymentMethod->fee;
        
        // Apply promo code if provided
        $discount = 0;
        $promoCodeId = null;
        
        if (!empty($checkoutData['promo_code'])) {
            $promoCode = PromoCode::where('code', $checkoutData['promo_code'])->active()->first();
            if ($promoCode) {
                $discount = $promoCode->calculateDiscount($subtotal);
                $promoCodeId = $promoCode->id;
            }
        }
        
        // Calculate total
        $total = $subtotal + $shippingCost + $paymentFee - $discount;
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'shipping_method_id' => $shippingMethod->id,
                'payment_method_id' => $paymentMethod->id,
                'promo_code_id' => $promoCodeId,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'payment_fee' => $paymentFee,
                'discount_amount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'transaction_id' => Str::uuid(),
                'terms_accepted' => true,
            ]);
            
            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'color_id' => $cartItem->color_id,
                    'size_id' => $cartItem->size_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'total' => $cartItem->product->price * $cartItem->quantity,
                ]);
            }
            
            // Process payment (placeholder for real payment gateway integration)
            // In a real implementation, you would integrate with a payment gateway here
            
            // Update promo code usage
            if ($promoCodeId) {
                PromoCode::find($promoCodeId)->incrementUsage();
            }
            
            // Clear cart
            Cart::where('user_id', $user->id)->delete();
            
            // Clear checkout data
            Session::forget('checkout_data');
            
            // Commit transaction
            DB::commit();
            
            // Redirect to success page
            return redirect()->route('checkout.success', ['order' => $order->id]);
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            // Log error
            Log::error('Checkout error: ' . $e->getMessage());
            
            // Redirect with error
            return redirect()->route('checkout.index')
                ->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    /**
     * Show the checkout success page.
     */
    public function success(Order $order)
    {
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            return redirect()->route('home');
        }
        
        return view('checkout.success', compact('order'));
    }
}
