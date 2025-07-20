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
            ->with(['product.images', 'product.categories', 'product.colors', 'product.sizes'])
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
            return redirect()->route('login')->with('error', 'Please login to add items to your wishlist.');
        }
        
        // Check if product exists
        $product = Product::find($productId);
        if (!$product) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 404);
            }
            
            return redirect()->back()->with('error', 'Product not found.');
        }
        
        // Check if already in wishlist
        $existingItem = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();
            
        if ($existingItem) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product already in wishlist.',
                    'in_wishlist' => true
                ]);
            }
            
            return redirect()->back()->with('info', 'Product already in your wishlist.');
        }
        
        // Add to wishlist
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId
        ]);
        
        if ($request->ajax()) {
            $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist.',
                'in_wishlist' => true,
                'wishlist_count' => $wishlistCount
            ]);
        }
        
        return redirect()->back()->with('success', 'Product added to your wishlist.');
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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to manage your wishlist.');
        }
        
        $item = Wishlist::where('id', $itemId)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$item) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in your wishlist.'
                ], 404);
            }
            
            return redirect()->back()->with('error', 'Item not found in your wishlist.');
        }
        
        $item->delete();
        
        if ($request->ajax()) {
            $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            return response()->json([
                'success' => true,
                'message' => 'Item removed from wishlist.',
                'wishlist_count' => $wishlistCount
            ]);
        }
        
        return redirect()->back()->with('success', 'Item removed from your wishlist.');
    }
    
    /**
     * Check if a product is in the user's wishlist
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request, $productId)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'in_wishlist' => false
                ]);
            }
            
            // Check if product exists
            $product = Product::find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.',
                    'in_wishlist' => false
                ], 404);
            }
            
            $inWishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->exists();
                
            return response()->json([
                'in_wishlist' => $inWishlist
            ]);
        } catch (\Exception $e) {
            Log::error('Wishlist check error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error checking wishlist status',
                'in_wishlist' => false
            ], 500);
        }
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
            return redirect()->route('login')->with('error', 'Please login to manage your wishlist.');
        }
        
        try {
            // Check if product exists
            $product = Product::find($productId);
            if (!$product) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product not found.',
                    ], 404);
                }
                
                return redirect()->back()->with('error', 'Product not found.');
            }
            
            // Check if already in wishlist
            $existingItem = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();
                
            if ($existingItem) {
                // Remove from wishlist
                $existingItem->delete();
                
                if ($request->ajax()) {
                    $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
                    return response()->json([
                        'success' => true,
                        'message' => 'Product removed from wishlist.',
                        'in_wishlist' => false,
                        'wishlist_count' => $wishlistCount
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
                    $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
                    return response()->json([
                        'success' => true,
                        'message' => 'Product added to wishlist.',
                        'in_wishlist' => true,
                        'wishlist_count' => $wishlistCount
                    ]);
                }
                
                return redirect()->back()->with('success', 'Product added to wishlist.');
            }
        } catch (\Exception $e) {
            Log::error('Wishlist toggle error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating your wishlist.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'An error occurred while updating your wishlist.');
        }
    }
    
    /**
     * Clear all items from the wishlist
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to manage your wishlist.');
        }
        
        Wishlist::where('user_id', Auth::id())->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared.',
                'wishlist_count' => 0
            ]);
        }
        
        return redirect()->back()->with('success', 'Your wishlist has been cleared.');
    }
} 