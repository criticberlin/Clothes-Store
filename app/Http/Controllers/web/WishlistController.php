<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your wishlist.');
        }
        
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with(['product.images'])
            ->latest()
            ->get();
            
        return view('wishlist.index', compact('wishlistItems'));
    }
    
    /**
     * Add a product to the wishlist
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function add(Request $request, $productId)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to add items to your wishlist.'
                ], 401);
            }
            
            return redirect()->route('login')->with('error', 'Please login to add items to your wishlist.');
        }
        
        try {
            // Find the product
            $product = Product::findOrFail($productId);
            
            // Check if already in wishlist
            $existingItem = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();
                
            if ($existingItem) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Product is already in your wishlist.',
                        'exists' => true
                    ]);
                }
                
                return redirect()->back()->with('info', 'Product is already in your wishlist.');
            }
            
            // Add to wishlist
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to wishlist successfully!',
                    'exists' => false
                ]);
            }
            
            return redirect()->back()->with('success', 'Product added to wishlist successfully!');
        } catch (\Exception $e) {
            Log::error('Wishlist add error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add product to wishlist.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to add product to wishlist.');
        }
    }
    
    /**
     * Remove a product from the wishlist
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $itemId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request, $itemId)
    {
        try {
            $wishlistItem = Wishlist::where('id', $itemId)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $wishlistItem->delete();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from wishlist successfully!'
                ]);
            }
            
            return redirect()->back()->with('success', 'Product removed from wishlist successfully!');
        } catch (\Exception $e) {
            Log::error('Wishlist remove error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to remove product from wishlist.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to remove product from wishlist.');
        }
    }
    
    /**
     * Check if a product is in the wishlist
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'in_wishlist' => false
            ]);
        }
        
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();
            
        return response()->json([
            'in_wishlist' => $exists
        ]);
    }
    
    /**
     * Toggle a product in the wishlist (add if not exists, remove if exists)
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggle(Request $request, $productId)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to manage your wishlist.'
                ], 401);
            }
            
            return redirect()->route('login')->with('error', 'Please login to manage your wishlist.');
        }
        
        try {
            // Check if already in wishlist
            $existingItem = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();
                
            if ($existingItem) {
                // Remove from wishlist
                $existingItem->delete();
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Product removed from wishlist.',
                        'in_wishlist' => false
                    ]);
                }
                
                return redirect()->back()->with('success', 'Product removed from wishlist.');
            } else {
                // Add to wishlist
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId
                ]);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Product added to wishlist.',
                        'in_wishlist' => true
                    ]);
                }
                
                return redirect()->back()->with('success', 'Product added to wishlist.');
            }
        } catch (\Exception $e) {
            Log::error('Wishlist toggle error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update wishlist.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update wishlist.');
        }
    }
}
