<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store a new product rating
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($productId);
        
        // Check if user already rated this product
        $existingRating = ProductRating::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();
            
        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
            
            return redirect()->back()->with('success', 'Your rating has been updated!');
        }
        
        // Create new rating
        ProductRating::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);
        
        return redirect()->back()->with('success', 'Thank you for your rating!');
    }
    
    /**
     * Delete a product rating
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $rating = ProductRating::findOrFail($id);
        
        // Check if user owns this rating or is admin
        if ($rating->user_id !== Auth::id() && !Auth::user()->roles->contains('name', 'admin')) {
            return redirect()->back()->with('error', 'You do not have permission to delete this rating.');
        }
        
        $rating->delete();
        
        return redirect()->back()->with('success', 'Rating has been deleted.');
    }
} 