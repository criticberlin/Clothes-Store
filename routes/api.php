<?php

use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [UsersController::class, 'login']);
Route::get('users', [UsersController::class, 'users'])->middleware('auth:api');
Route::get('logout', [UsersController::class, 'logout'])->middleware('auth:api');

// Search endpoint
Route::get('search', [SearchController::class, 'search']);

// Product details endpoint for quick view
Route::get('products/{id}', [ProductsController::class, 'show']);

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
})->middleware('api');

