<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the payment methods.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        
        return view('admin.payment.index', compact('paymentMethods'));
    }
    
    /**
     * Show the form for creating a new payment method.
     */
    public function create()
    {
        return view('admin.payment.create');
    }
    
    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
            'fee' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'config' => 'nullable|json',
        ]);
        
        PaymentMethod::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'icon' => $request->icon,
            'fee' => $request->fee,
            'is_active' => $request->is_active ?? true,
            'config' => $request->config,
        ]);
        
        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }
    
    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment.edit', compact('paymentMethod'));
    }
    
    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code,' . $paymentMethod->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
            'fee' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'config' => 'nullable|json',
        ]);
        
        $paymentMethod->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'icon' => $request->icon,
            'fee' => $request->fee,
            'is_active' => $request->is_active ?? false,
            'config' => $request->config,
        ]);
        
        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }
    
    /**
     * Toggle the active status of the specified payment method.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active,
        ]);
        
        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method status updated successfully.');
    }
    
    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Check if payment method is used in any orders
        if ($paymentMethod->orders()->count() > 0) {
            return redirect()->route('admin.payment-methods.index')
                ->with('error', 'Cannot delete payment method that is used in orders.');
        }
        
        $paymentMethod->delete();
        
        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }
}
