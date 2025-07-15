<?php
/**
 * Direct API search handler for MyClothes store
 * This file provides a fallback search endpoint when Laravel routes aren't working correctly
 */

// Bootstrap Laravel application
require __DIR__.'/../vendor/autoload.php';

// Set error handling
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Initialize app
try {
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    // Handle request
    $request = Illuminate\Http\Request::capture();
    $response = $kernel->handle($request);
    
    // Set headers for JSON API response
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    
    // Get search query
    $query = $_GET['q'] ?? null;
    $categoryId = $_GET['category_id'] ?? null;
    
    // Early response if no query
    if (!$query || strlen($query) < 2) {
        echo json_encode([
            'success' => false,
            'message' => 'Search query is required (minimum 2 characters)',
            'products' => []
        ]);
        exit;
    }
    
    try {
        // Get the database connection
        $db = $app->make('db');
        
        // Get the currency service for price formatting
        $currencyService = $app->make('App\Services\CurrencyService');
        
        // Build query for products
        $productsQuery = $db->table('products')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                 ->orWhere('description', 'like', "%{$query}%")
                 ->orWhere('code', 'like', "%{$query}%");
            });
        
        // Filter by category if provided
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }
        
        // Get products
        $products = $productsQuery->limit(10)->get();
        
        // Transform results
        $results = [];
        foreach ($products as $product) {
            // Get category name
            $categoryName = 'Uncategorized';
            if (!empty($product->category_id)) {
                $category = $db->table('categories')->where('id', $product->category_id)->first();
                if ($category) {
                    $categoryName = $category->name;
                }
            }
            
            // Format image URL
            $imageUrl = $product->image ?? 'images/placeholder.jpg';
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $imageUrl = $request->getSchemeAndHttpHost() . '/' . ltrim($imageUrl, '/');
            }
            
            // Add to results
            $results[] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug ?? $product->id,
                'description' => !empty($product->description) ? substr($product->description, 0, 100) : '',
                'price' => $product->price,
                'formatted_price' => $currencyService->formatPrice($product->price),
                'image' => $imageUrl,
                'quantity' => $product->quantity ?? 0,
                'category_name' => $categoryName
            ];
        }
        
        // Return response
        echo json_encode([
            'success' => true,
            'products' => $results,
            'query' => $query,
            'count' => count($results)
        ]);
        
    } catch (Exception $e) {
        // Log error
        error_log('Search API error: ' . $e->getMessage());
        
        // Return error response
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred during search',
            'error' => $e->getMessage(),
            'products' => []
        ]);
    }

    // Terminate Laravel app
    $kernel->terminate($request, $response);
} catch (Exception $e) {
    // Fatal application error
    error_log('Fatal application error: ' . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Application error',
        'error' => $e->getMessage(),
        'products' => []
    ]);
} 