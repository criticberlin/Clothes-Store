<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Symfony\Component\HttpFoundation\Response;

class CheckoutCartNotEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->count();
            
            if ($cartItems === 0) {
                return redirect()->route('cart.index')
                    ->with('error', 'Your cart is empty. Please add items before proceeding to checkout.');
            }
        }
        
        return $next($request);
    }
}
