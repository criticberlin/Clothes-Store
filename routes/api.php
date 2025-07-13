<?php

use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\api\SearchController;
use App\Http\Controllers\api\ProductsController;
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
