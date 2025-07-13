<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Get product details for quick view
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::with(['categories'])->find($id);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        // Get the primary category
        $categoryName = '';
        if ($product->categories()->first()) {
            $categoryName = $product->categories()->first()->name;
        }
        
        // Format the product price based on current currency
        $price = $product->price;
        $formattedPrice = number_format($price, 2) . ' ' . config('app.currency_symbol', '$');
        
        // Get image URL using the accessor
        $imageUrl = $product->image_url;
        
        // Get rating information
        $averageRating = $product->average_rating;
        $ratingsCount = $product->ratings_count;
        
        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'formatted_price' => $formattedPrice,
                'description' => $product->description,
                'image' => $imageUrl,
                'category_name' => $categoryName,
                'code' => $product->code,
                'quantity' => $product->quantity,
                'average_rating' => $averageRating,
                'ratings_count' => $ratingsCount,
            ]
        ]);
    }
} 