<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the currencies.
     */
    public function index()
    {
        $currencies = Currency::orderBy('is_default', 'desc')->get();
        $defaultCurrency = Currency::where('is_default', true)->first();
        
        return view('admin.currencies.index', [
            'currencies' => $currencies,
            'defaultCurrency' => $defaultCurrency
        ]);
    }

    /**
     * Display the specified currency for editing.
     */
    public function edit(Currency $currency)
    {
        return view('admin.currencies.edit', [
            'currency' => $currency
        ]);
    }

    /**
     * Update the specified currency.
     */
    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbol_en' => 'required|string|max:10',
            'symbol_ar' => 'required|string|max:10',
            'rate' => 'required|numeric|min:0.000001',
        ]);

        $currency->update($validated);

        return redirect()->route('admin.currencies.index')
            ->with('success', 'Currency updated successfully');
    }

    /**
     * Toggle the active status of the specified currency.
     */
    public function toggleStatus(Currency $currency)
    {
        // Don't allow deactivating the default currency
        if ($currency->is_default) {
            return redirect()->route('admin.currencies.index')
                ->with('error', 'Cannot deactivate the default currency');
        }

        $currency->update([
            'is_active' => !$currency->is_active
        ]);

        return redirect()->route('admin.currencies.index')
            ->with('success', 'Currency status updated successfully');
    }
} 