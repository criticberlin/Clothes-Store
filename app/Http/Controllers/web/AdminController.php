<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Get counts for dashboard stats
        $userCount = User::count();
        $productCount = Product::count();
        $orderCount = Order::count();
        $revenue = Order::where('status', 'completed')->sum('total_amount');
        $pendingOrderCount = Order::where('status', 'pending')->count();
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // Get popular products (most ordered)
        $popularProducts = Product::orderBy('quantity', 'desc')
            ->take(5)
            ->get();
            
        // Check if support tickets model exists and get recent tickets
        $recentTickets = [];
        $hasTickets = false;
        
        if (class_exists('\App\Models\SupportTicket')) {
            try {
                $recentTickets = \App\Models\SupportTicket::with('user')
                    ->latest()
                    ->limit(5)
                    ->get();
                $hasTickets = true;
            } catch (\Exception $e) {
                $hasTickets = false;
            }
        }
        
        return view('admin.dashboard', compact(
            'userCount', 
            'productCount', 
            'orderCount', 
            'pendingOrderCount', 
            'revenue', 
            'recentOrders', 
            'popularProducts',
            'recentTickets',
            'hasTickets'
        ));
    }
    
    /**
     * Display users list
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        $roles = \App\Models\Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }
    
    /**
     * Display products list
     */
    public function products()
    {
        $products = Product::with(['categories', 'ratings'])->orderBy('created_at', 'desc')->paginate(10);
        
        // Calculate average rating for each product
        $products->each(function ($product) {
            $product->avg_rating = $product->average_rating;
            $product->total_ratings = $product->ratings_count;
        });
        
        return view('admin.products.index', compact('products'));
    }
    
    /**
     * Display orders list
     */
    public function orders()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Display customer list
     */
    public function customers()
    {
        $customers = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.customers.index', compact('customers'));
    }
    
    /**
     * Display categories list
     */
    public function categories()
    {
        $categories = \App\Models\Category::with(['parent', 'children', 'products'])
            ->orderBy('name')
            ->paginate(15);
        
        return view('admin.categories.index', compact('categories'));
    }
    
    /**
     * Display reports and analytics
     */
    public function reports()
    {
        // Monthly sales data
        $monthlySales = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();
        
        return view('admin.reports.index', compact('monthlySales'));
    }
    
    /**
     * Display store information settings
     */
    public function storeInformation()
    {
        return view('admin.settings.store-information');
    }
    
    /**
     * Display payment settings
     */
    public function paymentSettings()
    {
        return view('admin.settings.payment');
    }
    
    /**
     * Display shipping settings
     */
    public function shippingSettings()
    {
        return view('admin.settings.shipping');
    }
    
    /**
     * Display email settings
     */
    public function emailSettings()
    {
        return view('admin.settings.email');
    }
    
    /**
     * Display currency management page
     */
    public function currencies()
    {
        // Now directly return the currencies index view
        $currencies = Currency::orderBy('is_default', 'desc')->get();
        $defaultCurrency = Currency::where('is_default', true)->first();
        
        return view('admin.currencies.index', [
            'currencies' => $currencies,
            'defaultCurrency' => $defaultCurrency
        ]);
    }
    
    /**
     * Update currencies
     */
    public function updateCurrencies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currencies' => 'required|array',
            'currencies.*.id' => 'required|exists:currencies,id',
            'currencies.*.rate' => 'required|numeric|min:0.000001',
            'currencies.*.symbol_en' => 'required|string|max:10',
            'currencies.*.symbol_ar' => 'required|string|max:10',
            'currencies.*.is_active' => 'boolean',
            'default_currency' => 'required|exists:currencies,id'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        

        
        // Flash success message
        session()->flash('success', 'Currencies updated successfully!');
        
        return redirect()->route('admin.currencies.index');
    }
    
    /**
     * Assign a role to a user
     */
    public function assignRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user = User::find($request->user_id);
        $user->roles()->sync([$request->role_id]);
        
        return redirect()->back()
            ->with('success', 'Role assigned successfully!');
    }
}
