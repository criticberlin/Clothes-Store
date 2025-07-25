<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\SocialAuthController;
use App\Http\Controllers\Web\SupportTicketController;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\PreferenceController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Web\RatingController;
use App\Http\Controllers\Web\LanguageController;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Preferences Routes
Route::post('/preferences/language', [PreferenceController::class, 'setLanguage'])->name('preferences.language');
Route::get('/preferences/language', [PreferenceController::class, 'setLanguage'])->name('preferences.language.get');
Route::get('/set-language/{lang}', [PreferenceController::class, 'switchLanguage'])->name('set.language');
Route::post('/preferences/theme', [PreferenceController::class, 'setTheme'])->name('preferences.theme');
Route::get('/theme/toggle', [PreferenceController::class, 'toggleTheme'])->name('theme.toggle');
Route::get('/theme/{theme}', [PreferenceController::class, 'setTheme'])->name('theme.set');
Route::post('/preferences/currency', [PreferenceController::class, 'setCurrency'])->name('preferences.currency');
Route::get('/preferences/clear', [PreferenceController::class, 'clearPreferences'])->name('preferences.clear');
Route::get('/currencies/list', function() {
    return response()->json(App\Models\Currency::getActiveCurrencies());
})->name('currencies.list');

// Language Routes
Route::get('/locale/{locale}', [LanguageController::class, 'switchLanguage'])->name('language.switch');

// Create a new HomeController for the root route
Route::get('/', [HomeController::class, 'index'])->name('home');

//  User Authentication
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// Profile Management
Route::get('verify', [UsersController::class, 'verify'])->name('verify');
Route::get('/forgot-password', [UsersController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [UsersController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [UsersController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [UsersController::class, 'resetPassword'])->name('password.update');

 // User Listing and Search

// User CRUD Operations
Route::get('/users/create', [UsersController::class, 'createRoll'])->name('users_create');
Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users_edit');
Route::post('/users/{user}/save', [UsersController::class, 'save'])->name('users_save');
Route::delete('/users/{user}/delete', [UsersController::class, 'delete'])->name('users_delete');
// Password Management
Route::get('/users/{user}/edit-password', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('/users/{user}/save-password', [UsersController::class, 'savePassword'])->name('save_password');

// User Profile
Route::get('/users/{user}/profile', [UsersController::class, 'profile'])->name('profile');

// Admin Routes
Route::prefix('admin')->middleware(['auth:web', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard'); // Add a root route for admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Admin User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.list');
    Route::get('/users/create', [UsersController::class, 'createRoll'])->name('admin.users.create');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('admin.users.edit');
    Route::post('/users/{user}/save', [UsersController::class, 'save'])->name('admin.users.save');
    Route::delete('/users/{user}/delete', [UsersController::class, 'delete'])->name('admin.users.delete');
    
    // Admin Product Management
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products.list');
    Route::get('/products/create', [ProductsController::class, 'edit'])->name('admin.products.create');
    Route::post('/products/store', [ProductsController::class, 'save'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('admin.products.edit');
    Route::post('/products/{product?}/save', [ProductsController::class, 'save'])->name('admin.products.save');
    Route::delete('/products/{product}/delete', [ProductsController::class, 'delete'])->name('admin.products.delete');
    
    // Admin Category Management
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories.index');
    Route::get('/categories/create', [App\Http\Controllers\Web\CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [App\Http\Controllers\Web\CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [App\Http\Controllers\Web\CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [App\Http\Controllers\Web\CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\Web\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/categories/children', [App\Http\Controllers\Web\CategoryController::class, 'getChildCategories'])->name('admin.categories.children');
    
    // Admin Order Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders.list');
    Route::get('/orders/{order}', [CartController::class, 'adminOrderDetails'])->name('admin.orders.details');
    Route::patch('/orders/{order}/update-status', [CartController::class, 'updateStatus'])->name('admin.orders.update-status');
    
    // Admin Customer Management
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers.list');
    
    // Admin Reports & Analytics
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    
    // Admin Settings
    Route::get('/settings/store-information', [AdminController::class, 'storeInformation'])->name('admin.settings');
    Route::get('/settings/payment', [AdminController::class, 'paymentSettings'])->name('admin.settings.payment');
    Route::get('/settings/shipping', [AdminController::class, 'shippingSettings'])->name('admin.settings.shipping');
    Route::get('/settings/email', [AdminController::class, 'emailSettings'])->name('admin.settings.email');
    
    // Admin Preferences
    Route::post('/preferences/currency', [App\Http\Controllers\Admin\PreferenceController::class, 'updateCurrency'])->name('admin.preferences.currency');
    
    // Admin Currency Management
    Route::get('/currencies', [AdminController::class, 'currencies'])->name('admin.currencies');
    Route::post('/currencies', [AdminController::class, 'updateCurrencies'])->name('admin.updateCurrencies');
    
    // Currency Management
    Route::prefix('currencies')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\CurrencyController::class, 'index'])->name('admin.currencies.index');
        Route::get('/{currency}/edit', [App\Http\Controllers\Admin\CurrencyController::class, 'edit'])->name('admin.currencies.edit');
        Route::put('/{currency}', [App\Http\Controllers\Admin\CurrencyController::class, 'update'])->name('admin.currencies.update');
        Route::patch('/{currency}/toggle-status', [App\Http\Controllers\Admin\CurrencyController::class, 'toggleStatus'])->name('admin.currencies.toggle-status');
    });
    
    // Admin Support Ticket Management
    Route::get('/support', [App\Http\Controllers\Admin\SupportTicketController::class, 'index'])->name('admin.support.index');
    Route::get('/support/{ticket}', [App\Http\Controllers\Admin\SupportTicketController::class, 'show'])->name('admin.support.show');
    Route::post('/support/{ticket}/reply', [App\Http\Controllers\Admin\SupportTicketController::class, 'reply'])->name('admin.support.reply');
    Route::post('/support/{ticket}/close', [App\Http\Controllers\Admin\SupportTicketController::class, 'close'])->name('admin.support.close');
    
    // Admin Shipping Management
    Route::prefix('shipping')->name('admin.shipping.')->group(function () {
        // Governorates
        Route::get('/governorates', [App\Http\Controllers\Admin\ShippingController::class, 'governorates'])->name('governorates');
        Route::get('/governorates/create', [App\Http\Controllers\Admin\ShippingController::class, 'createGovernorate'])->name('governorates.create');
        Route::post('/governorates', [App\Http\Controllers\Admin\ShippingController::class, 'storeGovernorate'])->name('governorates.store');
        Route::get('/governorates/{governorate}/edit', [App\Http\Controllers\Admin\ShippingController::class, 'editGovernorate'])->name('governorates.edit');
        Route::put('/governorates/{governorate}', [App\Http\Controllers\Admin\ShippingController::class, 'updateGovernorate'])->name('governorates.update');
        Route::delete('/governorates/{governorate}', [App\Http\Controllers\Admin\ShippingController::class, 'destroyGovernorate'])->name('governorates.destroy');
        
        // Cities
        Route::get('/cities', [App\Http\Controllers\Admin\ShippingController::class, 'cities'])->name('cities');
        Route::get('/cities/create', [App\Http\Controllers\Admin\ShippingController::class, 'createCity'])->name('cities.create');
        Route::post('/cities', [App\Http\Controllers\Admin\ShippingController::class, 'storeCity'])->name('cities.store');
        Route::get('/cities/{city}/edit', [App\Http\Controllers\Admin\ShippingController::class, 'editCity'])->name('cities.edit');
        Route::put('/cities/{city}', [App\Http\Controllers\Admin\ShippingController::class, 'updateCity'])->name('cities.update');
        Route::delete('/cities/{city}', [App\Http\Controllers\Admin\ShippingController::class, 'destroyCity'])->name('cities.destroy');
        
        // Shipping Methods
        Route::get('/methods', [App\Http\Controllers\Admin\ShippingController::class, 'methods'])->name('methods');
        Route::get('/methods/create', [App\Http\Controllers\Admin\ShippingController::class, 'createMethod'])->name('methods.create');
        Route::post('/methods', [App\Http\Controllers\Admin\ShippingController::class, 'storeMethod'])->name('methods.store');
        Route::get('/methods/{method}/edit', [App\Http\Controllers\Admin\ShippingController::class, 'editMethod'])->name('methods.edit');
        Route::put('/methods/{method}', [App\Http\Controllers\Admin\ShippingController::class, 'updateMethod'])->name('methods.update');
        Route::patch('/methods/{method}/toggle', [App\Http\Controllers\Admin\ShippingController::class, 'toggleMethodStatus'])->name('methods.toggle');
        Route::delete('/methods/{method}', [App\Http\Controllers\Admin\ShippingController::class, 'destroyMethod'])->name('methods.delete');
    });
    
    // Admin Payment Methods Management
    Route::prefix('payment')->name('admin.payment.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\PaymentMethodController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\PaymentMethodController::class, 'store'])->name('store');
        Route::get('/{paymentMethod}/edit', [App\Http\Controllers\Admin\PaymentMethodController::class, 'edit'])->name('edit');
        Route::put('/{paymentMethod}', [App\Http\Controllers\Admin\PaymentMethodController::class, 'update'])->name('update');
        Route::patch('/{paymentMethod}/toggle', [App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleStatus'])->name('toggle');
        Route::delete('/{paymentMethod}', [App\Http\Controllers\Admin\PaymentMethodController::class, 'destroy'])->name('destroy');
    });
    
    // Admin Promo Codes Management
    Route::prefix('promo-codes')->name('admin.promo-codes.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PromoCodeController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\PromoCodeController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\PromoCodeController::class, 'store'])->name('store');
        Route::get('/{promoCode}/edit', [App\Http\Controllers\Admin\PromoCodeController::class, 'edit'])->name('edit');
        Route::put('/{promoCode}', [App\Http\Controllers\Admin\PromoCodeController::class, 'update'])->name('update');
        Route::patch('/{promoCode}/toggle', [App\Http\Controllers\Admin\PromoCodeController::class, 'toggleStatus'])->name('toggle');
        Route::patch('/{promoCode}/reset', [App\Http\Controllers\Admin\PromoCodeController::class, 'resetUsage'])->name('reset');
        Route::delete('/{promoCode}', [App\Http\Controllers\Admin\PromoCodeController::class, 'destroy'])->name('destroy');
    });
});

// Public product routes
Route::get('/category', [ProductsController::class, 'category'])->name('products.category');
Route::get('/category/{category}', [ProductsController::class, 'ListByCategory'])->name('products.byCategory');
Route::get('/products', [ProductsController::class, 'index'])->name('products.list');
Route::get('/products/search', [ProductsController::class, 'search'])->name('products.search');
Route::get('/test-products', [App\Http\Controllers\Web\TestProductController::class, 'index'])->name('test.products');
Route::get('/product/{id}', [ProductsController::class, 'productDetails'])->name('products.details');

// Static pages
Route::get('/about', [App\Http\Controllers\Web\PageController::class, 'about'])->name('pages.about');
Route::get('/faq', [App\Http\Controllers\Web\PageController::class, 'faq'])->name('pages.faq');
Route::get('/contact', [App\Http\Controllers\Web\PageController::class, 'contact'])->name('pages.contact');
Route::get('/privacy', [App\Http\Controllers\Web\PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms', [App\Http\Controllers\Web\PageController::class, 'terms'])->name('pages.terms');
Route::get('/shipping', [App\Http\Controllers\Web\PageController::class, 'shipping'])->name('pages.shipping');
Route::get('/returns', [App\Http\Controllers\Web\PageController::class, 'returns'])->name('pages.returns');

// Protected product management routes
Route::middleware(['auth:web'])->group(function () {
    Route::get('/manage', [ProductsController::class, 'manage'])->name('products.manage');
    Route::get('/manage/edit/{product?}', [ProductsController::class, 'edit'])->name('products.edit');
    Route::post('/manage/save/{product?}', [ProductsController::class, 'save'])->name('products.save');
    Route::delete('/manage/delete/{product}', [ProductsController::class, 'delete'])->name('products.delete');
});

//  Social Authentication
Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
Route::get('auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);
Route::get('/auth/github/redirect', [SocialAuthController::class, 'redirectToGithub'])->name('github.redirect');
Route::get('/auth/github/callback', [SocialAuthController::class, 'handleGithubCallback']);

// Cart routes
Route::middleware(['auth:web'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/remove/{cartId}', [CartController::class, 'remove'])->name('cart.remove.get'); // Temporary for debugging
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear.get'); // Temporary for debugging

    // Checkout routes
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::post('/checkout/buy-now/{product}', [CartController::class, 'buyNow'])->name('checkout.buy-now');
    Route::get('/checkout/success', [CartController::class, 'checkoutSuccess'])->name('checkout.success');


    // Orders route
    Route::get('/orders', [CartController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [CartController::class, 'orderDetails'])->name('orders.details');

    // Wishlist routes
    Route::get('/wishlist', [App\Http\Controllers\Web\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{productId}', [App\Http\Controllers\Web\WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{itemId}', [App\Http\Controllers\Web\WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/wishlist/check/{productId}', [App\Http\Controllers\Web\WishlistController::class, 'check'])->name('wishlist.check');
    Route::post('/wishlist/toggle/{productId}', [App\Http\Controllers\Web\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/clear', [App\Http\Controllers\Web\WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::get('/wishlist/clear', [App\Http\Controllers\Web\WishlistController::class, 'clear'])->name('wishlist.clear.get'); // Temporary for debugging
});

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [App\Http\Controllers\Web\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/address', [App\Http\Controllers\Web\CheckoutController::class, 'saveAddress'])->name('checkout.address');
    Route::post('/checkout/shipping', [App\Http\Controllers\Web\CheckoutController::class, 'saveShipping'])->name('checkout.shipping');
    Route::post('/checkout/payment', [App\Http\Controllers\Web\CheckoutController::class, 'savePayment'])->name('checkout.payment');
    Route::post('/checkout/promo-code', [App\Http\Controllers\Web\CheckoutController::class, 'applyPromoCode'])->name('checkout.promo-code');
    Route::delete('/checkout/promo-code', [App\Http\Controllers\Web\CheckoutController::class, 'removePromoCode'])->name('checkout.promo-code.remove');
    Route::post('/checkout/process', [App\Http\Controllers\Web\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [App\Http\Controllers\Web\CheckoutController::class, 'success'])->name('checkout.success');
});

// Shipping Address Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/shipping', [App\Http\Controllers\Web\ShippingController::class, 'index'])->name('shipping.index');
    Route::get('/shipping/create', [App\Http\Controllers\Web\ShippingController::class, 'create'])->name('shipping.create');
    Route::post('/shipping', [App\Http\Controllers\Web\ShippingController::class, 'store'])->name('shipping.store');
    Route::get('/shipping/{address}/edit', [App\Http\Controllers\Web\ShippingController::class, 'edit'])->name('shipping.edit');
    Route::put('/shipping/{address}', [App\Http\Controllers\Web\ShippingController::class, 'update'])->name('shipping.update');
    Route::delete('/shipping/{address}', [App\Http\Controllers\Web\ShippingController::class, 'destroy'])->name('shipping.destroy');
    Route::post('/shipping/{address}/default', [App\Http\Controllers\Web\ShippingController::class, 'setDefault'])->name('shipping.default');
    Route::post('/shipping/cities', [App\Http\Controllers\Web\ShippingController::class, 'getCities'])->name('shipping.cities');
});

// Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/{order}/process', [App\Http\Controllers\Web\PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/methods', [App\Http\Controllers\Web\PaymentController::class, 'savedMethods'])->name('payment.methods');
});

// Promo code routes
Route::middleware(['auth:web'])->group(function () {
    Route::post('/promo-code/apply', [App\Http\Controllers\Web\PromoCodeController::class, 'apply'])->name('promo-code.apply');
    Route::delete('/promo-code/remove', [App\Http\Controllers\Web\PromoCodeController::class, 'remove'])->name('promo-code.remove');
    Route::post('/promo-code/validate', [App\Http\Controllers\Web\PromoCodeController::class, 'validateCode'])->name('promo-code.validate');
});


Route::get('/support', [App\Http\Controllers\Web\SupportTicketController::class, 'list'])->name('support.list');
Route::get('/support/add', [App\Http\Controllers\Web\SupportTicketController::class, 'add'])->name('support.add');
Route::post('/support', [App\Http\Controllers\Web\SupportTicketController::class, 'store'])->name('support.store');
Route::get('/support/{ticket}', [App\Http\Controllers\Web\SupportTicketController::class, 'show'])->name('support.show');

// 3D Customizer route
Route::get('/3d-customizer', function () {
    return redirect('/3D_Customizer/');
})->name('3d-customizer');

// Rating routes
Route::middleware(['auth'])->group(function () {
    Route::post('/products/{product}/rate', [RatingController::class, 'store'])->name('products.rate');
    Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
});

// Fallback API routes to ensure functionality
Route::get('/api/search', [App\Http\Controllers\Api\SearchController::class, 'search']);

// API status check route
Route::get('/api-status', function () {
    return response()->json([
        'status' => 'ok', 
        'message' => 'API routes are working',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Test route for CategoryController
Route::get('/test-category-controller', [App\Http\Controllers\Web\CategoryController::class, 'testController']);

// Test route for CSRF token
Route::post('/test-csrf', function() {
    return response()->json(['success' => true, 'message' => 'CSRF token is valid']);
});

// Debug routes
Route::get('/debug/category-relationships', function() {
    $relationships = DB::table('category_category')->get();
    $categories = \App\Models\Category::all()->keyBy('id');
    
    $result = [];
    foreach ($relationships as $rel) {
        $parent = $categories[$rel->parent_id] ?? null;
        $child = $categories[$rel->child_id] ?? null;
        
        $result[] = [
            'relationship_id' => $rel->id,
            'parent_id' => $rel->parent_id,
            'parent_name' => $parent ? $parent->name : 'Unknown',
            'parent_type' => $parent ? $parent->type : 'Unknown',
            'child_id' => $rel->child_id,
            'child_name' => $child ? $child->name : 'Unknown',
            'child_type' => $child ? $child->type : 'Unknown',
        ];
    }
    
    return response()->json($result);
});


