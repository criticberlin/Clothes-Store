<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Search products based on a query string with improved efficiency
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Log the search request for debugging
        Log::info('Search API called', [
            'query' => $request->q,
            'category_id' => $request->category_id
        ]);
        
        // Validate the request
        $request->validate([
            'q' => 'required|string|min:2',
            'category_id' => 'nullable|numeric'
        ]);
        
        $query = $request->q;
        $categoryId = $request->category_id;
        
        // Start building the query
        $products = Product::query()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%");
            });
        
        // Filter by category if provided
        if ($categoryId) {
            $products->where('category_id', $categoryId);
        }
        
        // Get the results
        $results = $products->limit(20)->get();
        
        // Transform the results
        $transformedResults = $results->map(function($product) {
            // Use a placeholder image if the product photo is not available
            $imagePath = $product->photo ? 'img/products/' . $product->photo : 'images/placeholder.jpg';
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug ?? $product->id,
                'description' => Str::limit($product->description, 100),
                'price' => $product->price,
                'formatted_price' => number_format($product->price, 2) . ' ' . config('app.currency_symbol', '$'),
                'image' => asset($imagePath),
                'quantity' => $product->quantity,
                'category_name' => ucfirst($product->category)
            ];
        });
        
        return response()->json([
            'success' => true,
            'products' => $transformedResults
        ]);
    }
} 