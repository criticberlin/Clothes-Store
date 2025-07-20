<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with(['categories', 'ratings'])->get();
        
        // Calculate average rating for each product
        $products->each(function ($product) {
            $product->avg_rating = $product->average_rating;
            $product->total_ratings = $product->ratings_count;
        });
        
        return view('admin.products.index', compact('products'));
    }
    
    /**
     * Display product ratings dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function ratings()
    {
        // Get top rated products
        $topRatedProducts = Product::withCount('ratings')
            ->having('ratings_count', '>', 0)
            ->orderByDesc(DB::raw('AVG(product_ratings.rating)'))
            ->limit(10)
            ->get();
            
        // Get products with most reviews
        $mostReviewedProducts = Product::withCount('ratings')
            ->orderByDesc('ratings_count')
            ->limit(10)
            ->get();
            
        // Get recent ratings
        $recentRatings = ProductRating::with(['product', 'user'])
            ->latest()
            ->limit(20)
            ->get();
            
        // Get rating distribution
        $ratingDistribution = ProductRating::select(DB::raw('rating, COUNT(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->pluck('count', 'rating')
            ->toArray();
            
        // Calculate overall average rating
        $overallAverage = ProductRating::avg('rating') ?? 0;
        
        return view('admin.products.ratings', compact(
            'topRatedProducts', 
            'mostReviewedProducts', 
            'recentRatings', 
            'ratingDistribution',
            'overallAverage'
        ));
    }
    
    /**
     * Approve or disapprove a rating.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleRatingApproval(Request $request, $id)
    {
        $rating = ProductRating::findOrFail($id);
        $rating->is_approved = !$rating->is_approved;
        $rating->save();
        
        return redirect()->back()->with('success', 
            $rating->is_approved ? 'Rating approved successfully.' : 'Rating disapproved successfully.');
    }
    
    /**
     * Delete a rating.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteRating($id)
    {
        $rating = ProductRating::findOrFail($id);
        $rating->delete();
        
        return redirect()->back()->with('success', 'Rating deleted successfully.');
    }
} 