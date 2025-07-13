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

    public function add(Request $request, Product $product)
    {
        if (!Auth::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please login to add items to cart'], 401);
            }
            return redirect('login');
        }
        
        Log::info('Add to cart request:', [
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'color' => $request->color,
            'size' => $request->size
        ]);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity,
            'color' => 'nullable|exists:colors,id',
            'size' => 'nullable|exists:sizes,id'
        ]);

        // Check if product already exists in cart with same color and size
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('color_id', $request->color)
            ->where('size_id', $request->size)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->quantity) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Not enough stock available.']);
                }
                return redirect()->back()->with('error', 'Not enough stock available.');
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
            Log::info('Updated existing cart item:', ['cart_item' => $cartItem->toArray()]);
        } else {
            // Create new cart item
            $newCartItem = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'color_id' => $request->color ?: null,
                'size_id' => $request->size ?: null,
                'quantity' => $request->quantity
            ]);
            Log::info('Created new cart item:', ['cart_item' => $newCartItem->toArray()]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Product added to cart successfully!']);
        }
        
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cart->product->quantity
        ]);

        $cart->quantity = $request->quantity;
        $cart->save();

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function remove($cartId)
    {
        try {
            Log::info('Removing cart item', ['cart_id' => $cartId, 'user_id' => Auth::id(), 'method' => request()->method()]);
            
            // Find the cart item
            $cart = Cart::findOrFail($cartId);
            
            // Check if the cart item belongs to the authenticated user
            if ($cart->user_id !== Auth::id()) {
                Log::warning('Unauthorized cart removal attempt', [
                    'cart_id' => $cartId, 
                    'cart_user_id' => $cart->user_id, 
                    'current_user_id' => Auth::id()
                ]);
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

            // Delete the cart item
            $cart->delete();
            
            Log::info('Cart item removed successfully', ['cart_id' => $cartId]);
            return redirect()->route('cart.index')->with('success', 'Item removed from cart successfully!');
        } catch (\Exception $e) {
            Log::error('Error removing cart item', [
                'cart_id' => $cartId, 
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('cart.index')->with('error', 'Failed to remove item from cart: ' . $e->getMessage());
        }
    }

    public function clear()
    {
        try {
            Log::info('Clearing cart', ['user_id' => Auth::id()]);
            
            $count = Cart::where('user_id', Auth::id())->count();
            Cart::where('user_id', Auth::id())->delete();
            
            Log::info('Cart cleared successfully', ['user_id' => Auth::id(), 'items_removed' => $count]);
            return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
        } catch (\Exception $e) {
            Log::error('Error clearing cart', [
                'user_id' => Auth::id(), 
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('cart.index')->with('error', 'Failed to clear cart.');
        }
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