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
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        
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
     * Display admin settings
     */
    public function settings()
    {
        $currencies = Currency::orderBy('is_default', 'desc')->get();
        
        return view('admin.settings.index', compact('currencies'));
    }
    
    /**
     * Display currency management page
     */
    public function currencies()
    {
        // Redirect to settings page with currencies section active
        return redirect()->route('admin.settings', ['section' => 'currencies']);
    }
    
    /**
     * Update currencies
     */
    public function updateCurrencies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currencies' => 'required|array',
            'currencies.*.id' => 'required|exists:currencies,id',
            'currencies.*.exchange_rate' => 'required|numeric|min:0',
            'currencies.*.is_active' => 'boolean',
            'default_currency' => 'required|exists:currencies,id'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update all currencies
        foreach ($request->currencies as $currencyData) {
            $currency = Currency::find($currencyData['id']);
            $currency->exchange_rate = $currencyData['exchange_rate'];
            $currency->is_active = $currencyData['is_active'] ?? false;
            $currency->is_default = ($currencyData['id'] == $request->default_currency);
            $currency->save();
        }
        
        return redirect()->route('admin.currencies')
            ->with('success', 'Currencies updated successfully!');
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
