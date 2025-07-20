<?php

use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('login', [UsersController::class, 'login']);
Route::get('search', [SearchController::class, 'search']);
Route::get('products/{id}', [ProductsController::class, 'show']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('users', [UsersController::class, 'users']);
    Route::get('logout', [UsersController::class, 'logout']);
    
    /**
     * Currency API Routes
     */
    Route::get('/format-price', function (Request $request) {
        $price = $request->input('price');
        $currencyCode = $request->input('currency_code');
        
        // Use the currency service to format the price
        $currencyService = app(\App\Services\CurrencyService::class);
        
        // Find the requested currency
        $currency = null;
        if ($currencyCode) {
            $currency = \App\Models\Currency::where('code', $currencyCode)
                ->where('is_active', true)
                ->first();
        }
        
        // Format the price
        $formattedPrice = $currencyService->formatPrice($price, $currency);
        
        return response()->json([
            'success' => true,
            'formatted_price' => $formattedPrice,
        ]);
    });
});

