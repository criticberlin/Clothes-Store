<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\web\UsersController;
use App\Http\Controllers\web\SocialAuthController;
use App\Http\Controllers\web\SupportTicketController;
use App\Http\Controllers\web\ProductsController;
use App\Http\Controllers\web\CartController;
use App\Http\Controllers\web\CheckoutController;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\web\AdminDashboardController;
use App\Http\Controllers\web\AdminController;
use App\Http\Controllers\PreferenceController;
use App\Http\Middleware\AdminMiddleware;

// Create a new HomeController for the root route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Theme, Language and Currency preferences
Route::get('/theme/toggle', [PreferenceController::class, 'toggleTheme'])->name('theme.toggle');
Route::get('/theme/{theme}', [PreferenceController::class, 'setTheme'])->name('theme.set');
Route::get('/language/{locale}', [PreferenceController::class, 'setLanguage'])->name('language.set');
Route::get('/currency/{currency}', [PreferenceController::class, 'setCurrency'])->name('currency.set');
Route::get('/currencies', [PreferenceController::class, 'getCurrencies'])->name('currencies.list');
// New routes for POST preferences
Route::post('/preferences/theme', [PreferenceController::class, 'setTheme'])->name('preferences.theme');
Route::post('/preferences/language', [PreferenceController::class, 'setLanguage'])->name('preferences.language');
Route::post('/preferences/currency', [PreferenceController::class, 'setCurrency'])->name('preferences.currency');

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
Route::get('/users/list', [UsersController::class, 'list'])->name('users.list');

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
Route::prefix('admin')->middleware(['auth:web', AdminMiddleware::class])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/assign-role', [AdminController::class, 'assignRole'])->name('admin.assign-role');
    
    // Admin User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.list');
    Route::get('/users/create', [UsersController::class, 'createRoll'])->name('admin.users.create');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('admin.users.edit');
    Route::post('/users/{user}/save', [UsersController::class, 'save'])->name('admin.users.save');
    Route::delete('/users/{user}/delete', [UsersController::class, 'delete'])->name('admin.users.delete');
    
    // Admin Product Management
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products.list');
    Route::get('/products/create', [ProductsController::class, 'edit'])->name('admin.products.create');
    Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('admin.products.edit');
    Route::post('/products/{product?}/save', [ProductsController::class, 'save'])->name('admin.products.save');
    Route::delete('/products/{product}/delete', [ProductsController::class, 'delete'])->name('admin.products.delete');
    
    // Admin Order Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders.list');
    Route::get('/orders/{order}', [CartController::class, 'adminOrderDetails'])->name('admin.orders.details');
    Route::patch('/orders/{order}/update-status', [CartController::class, 'updateStatus'])->name('admin.orders.update-status');
    
    // Admin Customer Management
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers.list');
    
    // Admin Reports & Analytics
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    
    // Admin Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    
    // Admin Currency Management
    Route::get('/currencies', [AdminController::class, 'currencies'])->name('admin.currencies');
    Route::post('/currencies', [AdminController::class, 'updateCurrencies'])->name('admin.currencies.update');
    
    // Admin Support Ticket Management
    Route::get('/support', [App\Http\Controllers\web\AdminSupportTicketController::class, 'index'])->name('admin.support.index');
    Route::get('/support/{ticket}', [App\Http\Controllers\web\AdminSupportTicketController::class, 'show'])->name('admin.support.show');
    Route::post('/support/{ticket}/reply', [App\Http\Controllers\web\AdminSupportTicketController::class, 'reply'])->name('admin.support.reply');
    Route::post('/support/{ticket}/close', [App\Http\Controllers\web\AdminSupportTicketController::class, 'close'])->name('admin.support.close');
});

// Public product routes
Route::get('/category', [ProductsController::class, 'category'])->name('products.category');
Route::get('/category/{category}', [ProductsController::class, 'ListByCategory'])->name('products.byCategory');
Route::get('/products', [ProductsController::class, 'index'])->name('products.list');
Route::get('/test-products', [App\Http\Controllers\web\TestProductController::class, 'index'])->name('test.products');
Route::get('/product/{id}', [ProductsController::class, 'productDetails'])->name('products.details');

// Static pages
Route::get('/about', [App\Http\Controllers\web\PageController::class, 'about'])->name('pages.about');
Route::get('/faq', [App\Http\Controllers\web\PageController::class, 'faq'])->name('pages.faq');
Route::get('/contact', [App\Http\Controllers\web\PageController::class, 'contact'])->name('pages.contact');
Route::get('/privacy', [App\Http\Controllers\web\PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms', [App\Http\Controllers\web\PageController::class, 'terms'])->name('pages.terms');
Route::get('/shipping', [App\Http\Controllers\web\PageController::class, 'shipping'])->name('pages.shipping');
Route::get('/returns', [App\Http\Controllers\web\PageController::class, 'returns'])->name('pages.returns');

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
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout routes
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::post('/checkout/buy-now/{product}', [CartController::class, 'buyNow'])->name('checkout.buy-now');
    Route::get('/checkout/success', [CartController::class, 'checkoutSuccess'])->name('checkout.success');


    // Orders route
    Route::get('/orders', [CartController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [CartController::class, 'orderDetails'])->name('orders.details');
});


Route::get('/support', [App\Http\Controllers\web\SupportTicketController::class, 'list'])->name('support.list');
Route::get('/support/add', [App\Http\Controllers\web\SupportTicketController::class, 'add'])->name('support.add');
Route::post('/support', [App\Http\Controllers\web\SupportTicketController::class, 'store'])->name('support.store');
Route::get('/support/{ticket}', [App\Http\Controllers\web\SupportTicketController::class, 'show'])->name('support.show');


