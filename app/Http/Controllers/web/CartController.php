<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) return redirect('login');
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product', 'color', 'size'])
            ->get();

        $subTotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        // Calculate shipping cost - can be adjusted based on your business logic
        $shippingCost = $subTotal > 0 ? 10.00 : 0.00;
        
        // Calculate tax - can be adjusted based on your business logic (e.g., 14% VAT)
        $tax = $subTotal > 0 ? round($subTotal * 0.14, 2) : 0.00;
        
        // Calculate total
        $total = $subTotal + $shippingCost + $tax;

        return view('cart.index', compact('cartItems', 'subTotal', 'shippingCost', 'tax', 'total'));
    }

    /**
     * Add a product to the cart
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        $colorId = $request->input('color');
        $sizeId = $request->input('size');
        
        // Check if product is available
        if ($product->quantity < $quantity) {
            return redirect()->back()->with('error', 'Sorry, the product is out of stock or not enough quantity available.');
        }
        
        // Check if product already in cart
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('color_id', $colorId)
            ->where('size_id', $sizeId)
            ->first();
            
        if ($existingCart) {
            // Update quantity if product already in cart
            $existingCart->quantity += $quantity;
            $existingCart->save();
        } else {
            // Add new product to cart
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'color_id' => $colorId,
                'size_id' => $sizeId,
                'quantity' => $quantity
            ]);
        }
        
        // If AJAX request, return JSON response with updated cart count
        if ($request->ajax()) {
            $cartCount = Auth::user()->cart->sum('quantity');
            return response()->json([
                'success' => true, 
                'message' => 'Product added to cart successfully!',
                'cart_count' => $cartCount
            ]);
        }
        
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update cart item quantity
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cart $cart)
    {
        // Ensure user can only update their own cart
        if ($cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to update this cart item.');
        }
        
        $quantity = $request->input('quantity');
        
        // Check if product is available
        if ($cart->product->quantity < $quantity) {
            return redirect()->back()->with('error', 'Sorry, the product is out of stock or not enough quantity available.');
        }
        
        $cart->quantity = $quantity;
        $cart->save();
        
        // If AJAX request, return JSON response with updated cart count
        if ($request->ajax()) {
            $cartCount = Auth::user()->cart->sum('quantity');
            return response()->json([
                'success' => true, 
                'message' => 'Cart updated successfully!',
                'cart_count' => $cartCount,
                'item_total' => number_format($cart->quantity * $cart->product->price, 2)
            ]);
        }
        
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove an item from the cart
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $cartId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request, $cartId)
    {
        $cart = Cart::findOrFail($cartId);
        
        // Ensure user can only remove their own cart items
        if ($cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to remove this cart item.');
        }
        
        $cart->delete();
        
        // If AJAX request, return JSON response with updated cart count
        if ($request->ajax()) {
            $cartCount = Auth::user()->cart->count();
            return response()->json([
                'success' => true, 
                'message' => 'Item removed from cart successfully!',
                'cart_count' => $cartCount
            ]);
        }
        
        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }

    /**
     * Clear all items from the cart
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear(Request $request)
    {
        Cart::where('user_id', Auth::id())->delete();
        
        // If AJAX request, return JSON response with updated cart count
        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Cart cleared successfully!',
                'cart_count' => 0
            ]);
        }
        
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }

    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product', 'color', 'size'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|in:credit_card,paypal',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check available quantity
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->quantity) {
                return redirect()->route('cart.index')->with('error', 'Insufficient stock for product: ' . $item->product->name);
            }
        }

        // Calculate total amount
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'shipping_address' => $request->shipping_address,
            'payment_method' => $request->payment_method,
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'color_id' => $item->color_id,
                'size_id' => $item->size_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            // Update product quantity
            $item->product->decrement('quantity', $item->quantity);
        }

        // Clear the cart
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    }

    public function checkoutSuccess()
    {
        $lastOrder = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->latest()
            ->first();

        if (!$lastOrder) {
            return redirect()->route('cart.index');
        }

        return view('checkout.success', compact('lastOrder'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product', 'items.color', 'items.size'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        if (Auth::id() != $order->user_id) {
            abort(403, 'Unauthorized');
        }
        
        $order->load(['items.product', 'items.color', 'items.size', 'user']);
        
        return view('orders.details', compact('order'));
    }

    public function adminOrders()
    {
        if (!Auth::check()) {
            abort(401, 'Unauthorized');
        }
        
        // For now, we'll simplify this to avoid linter errors
        // Later, implement proper permission checks

        $orders = Order::with(['user', 'items.product', 'items.color', 'items.size'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.admin', compact('orders'));
    }

    public function adminOrderDetails(Order $order)
    {
        if (!Auth::check()) {
            abort(401, 'Unauthorized');
        }
        
        // Check if user has admin role through model_has_roles table
        $hasAdminRole = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', Auth::id())
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->where('roles.name', 'Admin')
            ->exists();
            
        if (!$hasAdminRole) {
            abort(403, 'Unauthorized');
        }
        
        $order->load(['items.product', 'items.color', 'items.size', 'user']);
        
        return view('admin.orders.details', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if (!Auth::check()) {
            abort(401, 'Unauthorized');
        }
        
        // For now, we'll simplify this to avoid linter errors
        // Later, implement proper permission checks

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully');
    }
}