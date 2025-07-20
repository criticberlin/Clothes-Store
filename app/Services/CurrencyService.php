<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class CurrencyService
{
    /**
     * Fixed exchange rates relative to USD.
     */
    const EXCHANGE_RATES = [
        'USD' => 1,      // Base reference
        'EGP' => 50,     // Default store currency
        'EUR' => 0.87,
        'GBP' => 0.75,
        'SAR' => 3.79,
    ];
    
    /**
     * Get the active currencies.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCurrencies()
    {
        return Cache::remember('active_currencies', 60, function () {
            return Currency::where('is_active', true)->get();
        });
    }
    
    /**
     * Get the current currency based on user preference.
     *
     * @param bool $isAdmin Whether this is for an admin user
     * @return \App\Models\Currency
     */
    public function getCurrentCurrency($isAdmin = null)
    {
        // Auto-detect if this is an admin route if not specified
        if ($isAdmin === null) {
            $isAdmin = $this->isAdminRoute();
        }
        
        // Use admin-specific cookie/session if in admin area
        if ($isAdmin) {
            $currencyCode = request()->cookie('admin_currency') ?? 
                Session::get('admin_currency_code') ?? 
                Currency::where('is_default', true)->value('code') ?? 
                'EGP';
        } else {
            // For frontend users
            $currencyCode = request()->cookie('currency') ?? 
                Session::get('currency_code') ?? 
                Currency::where('is_default', true)->value('code') ?? 
                'EGP';
        }
        
        // Get the currency from database
        $currency = Currency::where('code', $currencyCode)
            ->where('is_active', true)
            ->first();
        
        // Fallback to default currency if not found
        if (!$currency) {
            $currency = Currency::where('is_default', true)->first();
            
            // Final fallback if no default currency is set
            if (!$currency) {
                $currency = Currency::where('code', 'EGP')->first();
            }
        }
        
        return $currency;
    }
    
    /**
     * Get the current currency code.
     *
     * @param bool $isAdmin Whether this is for an admin user
     * @return string
     */
    public function getCurrentCurrencyCode($isAdmin = null)
    {
        // Auto-detect if this is an admin route if not specified
        if ($isAdmin === null) {
            $isAdmin = $this->isAdminRoute();
        }
        
        // Use admin-specific cookie/session if in admin area
        if ($isAdmin) {
            return request()->cookie('admin_currency') ?? 
                Session::get('admin_currency_code') ?? 
                Currency::where('is_default', true)->value('code') ?? 
                'EGP';
        } else {
            // For frontend users
            return request()->cookie('currency') ?? 
                Session::get('currency_code') ?? 
                Currency::where('is_default', true)->value('code') ?? 
                'EGP';
        }
    }
    
    /**
     * Convert a price from the base currency (EGP) to the target currency.
     * 
     * @param float $price Price in EGP
     * @param \App\Models\Currency|null $targetCurrency Currency to convert to
     * @return float Converted price
     */
    public function convertPrice($price, $targetCurrency = null)
    {
        // Ensure price is numeric
        if (!is_numeric($price)) {
            if (is_array($price) || is_object($price)) {
                $price = 0;
            } else {
                $price = (float)$price;
            }
        }
        
        if (!$targetCurrency) {
            $targetCurrency = $this->getCurrentCurrency();
        }
        
        // If price is already in the target currency, return as is
        if ($targetCurrency->is_default) {
            return $price;
        }
        
        // Convert EGP to USD first (as reference)
        $priceInUsd = $price / self::EXCHANGE_RATES['EGP'];
        
        // Then convert USD to target currency
        return $priceInUsd * self::EXCHANGE_RATES[$targetCurrency->code];
    }
    
    /**
     * Format a price for display.
     * 
     * @param float $price Price in EGP
     * @param \App\Models\Currency|null $currency Currency to format in
     * @param bool $includeCode Whether to include the currency code
     * @return string Formatted price
     */
    public function formatPrice($price, $currency = null, $includeCode = false)
    {
        // Ensure price is numeric
        if (!is_numeric($price)) {
            if (is_array($price) || is_object($price)) {
                $price = 0;
            } else {
                $price = (float)$price;
            }
        }
        
        if (!$currency) {
            $currency = $this->getCurrentCurrency();
        }
        
        // Convert the price to the target currency
        $convertedPrice = $this->convertPrice($price, $currency);
        
        // Format the price
        $formattedPrice = number_format($convertedPrice, 2);
        
        // Get the appropriate symbol based on locale
        $symbol = $currency->getSymbolForCurrentLocale();
        
        // Construct the final formatted price
        $result = $symbol . $formattedPrice;
        
        // Add the currency code if requested
        if ($includeCode) {
            $result .= ' ' . $currency->code;
        }
        
        return $result;
    }
    
    /**
     * Calculate the exchange rate between two currencies.
     * 
     * @param string $fromCurrency Currency code to convert from
     * @param string $toCurrency Currency code to convert to
     * @return float Exchange rate
     */
    public function getExchangeRate($fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return 1;
        }
        
        // Calculate via USD as the reference
        $fromToUsd = 1 / self::EXCHANGE_RATES[$fromCurrency];
        $usdToTarget = self::EXCHANGE_RATES[$toCurrency];
        
        return $fromToUsd * $usdToTarget;
    }
    
    /**
     * Check if the current route is an admin route.
     * 
     * @return bool
     */
    private function isAdminRoute()
    {
        $routeName = Route::currentRouteName();
        return $routeName && (strpos($routeName, 'admin.') === 0);
    }
} 