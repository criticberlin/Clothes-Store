<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Search for products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q'); // Changed from 'query' to 'q' to match frontend
        
        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required'
            ]);
        }
        
        $results = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->limit(10)
            ->get();
            
        $currencyService = app(CurrencyService::class);
            
        $transformedResults = $results->map(function($product) use ($currencyService) {
            $category = $product->category ? $product->category->name : 'Uncategorized';
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug ?? $product->id,
                'description' => $product->description ? Str::limit($product->description, 100) : '',
                'price' => $product->price,
                'formatted_price' => $currencyService->formatPrice($product->price),
                'image' => $product->image_url ?? asset('images/placeholder.jpg'),
                'quantity' => $product->quantity ?? 0,
                'category_name' => $category
            ];
        });
        
        return response()->json([
            'success' => true,
            'products' => $transformedResults
        ]);
    }
} 