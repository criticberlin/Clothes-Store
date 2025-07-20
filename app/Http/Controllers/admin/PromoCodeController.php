<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the promo codes.
     */
    public function index()
    {
        $promoCodes = PromoCode::orderBy('created_at', 'desc')->get();
        
        return view('admin.promo-codes.index', compact('promoCodes'));
    }
    
    /**
     * Show the form for creating a new promo code.
     */
    public function create()
    {
        return view('admin.promo-codes.create');
    }
    
    /**
     * Store a newly created promo code in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);
        
        // Additional validation for percentage type
        if ($request->type === 'percentage' && $request->value > 100) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['value' => 'Percentage discount cannot exceed 100%.']);
        }
        
        PromoCode::create([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'min_order_amount' => $request->min_order_amount,
            'max_discount_amount' => $request->max_discount_amount,
            'usage_limit' => $request->usage_limit,
            'usage_count' => 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('admin.promo-codes.index')
            ->with('success', 'Promo code created successfully.');
    }
    
    /**
     * Show the form for editing the specified promo code.
     */
    public function edit(PromoCode $promoCode)
    {
        return view('admin.promo-codes.edit', compact('promoCode'));
    }
    
    /**
     * Update the specified promo code in storage.
     */
    public function update(Request $request, PromoCode $promoCode)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code,' . $promoCode->id,
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);
        
        // Additional validation for percentage type
        if ($request->type === 'percentage' && $request->value > 100) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['value' => 'Percentage discount cannot exceed 100%.']);
        }
        
        $promoCode->update([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'min_order_amount' => $request->min_order_amount,
            'max_discount_amount' => $request->max_discount_amount,
            'usage_limit' => $request->usage_limit,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? false,
        ]);
        
        return redirect()->route('admin.promo-codes.index')
            ->with('success', 'Promo code updated successfully.');
    }
    
    /**
     * Toggle the active status of the specified promo code.
     */
    public function toggleStatus(PromoCode $promoCode)
    {
        $promoCode->update([
            'is_active' => !$promoCode->is_active,
        ]);
        
        return redirect()->route('admin.promo-codes.index')
            ->with('success', 'Promo code status updated successfully.');
    }
    
    /**
     * Reset the usage count of the specified promo code.
     */
    public function resetUsage(PromoCode $promoCode)
    {
        $promoCode->update([
            'usage_count' => 0,
        ]);
        
        return redirect()->route('admin.promo-codes.index')
            ->with('success', 'Promo code usage count reset successfully.');
    }
    
    /**
     * Remove the specified promo code from storage.
     */
    public function destroy(PromoCode $promoCode)
    {
        // Check if promo code is used in any orders
        if ($promoCode->orders()->count() > 0) {
            return redirect()->route('admin.promo-codes.index')
                ->with('error', 'Cannot delete promo code that is used in orders.');
        }
        
        $promoCode->delete();
        
        return redirect()->route('admin.promo-codes.index')
            ->with('success', 'Promo code deleted successfully.');
    }
}
