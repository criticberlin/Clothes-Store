<?php
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Currency;

if (!function_exists('emailFromLoginCertificate')) {
    function emailFromLoginCertificate()
    {
        if (!isset($_SERVER['SSL_CLIENT_CERT'])) return null;

        $clientCertPEM = $_SERVER['SSL_CLIENT_CERT'];
        $certResource = openssl_x509_read($clientCertPEM);
        if(!$certResource) return null;
        $subject = openssl_x509_parse($certResource, false);
        if(!isset($subject['subject']['emailAddress'])) return null;
        return $subject['subject']['emailAddress'];
    }
}

if (!function_exists('lang')) {
    /**
     * Get translation for the given key
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function lang($key, $replace = [], $locale = null)
    {
        return __('general.' . $key, $replace, $locale);
    }
}

if (!function_exists('format_price')) {
    /**
     * Format price with the appropriate currency symbol and exchange rate
     *
     * @param float $price Price in EGP
     * @param string|null $currencyCode Optional currency code to use
     * @param bool $includeCode Whether to include the currency code in the output
     * @return string Formatted price
     */
    function format_price($price, $currencyCode = null, $includeCode = false)
    {
        // Get the CurrencyService
        $currencyService = app(\App\Services\CurrencyService::class);
        
        if ($currencyCode) {
            // If a specific currency code is provided, get that currency
            try {
                $currency = \App\Models\Currency::where('code', $currencyCode)
                    ->where('is_active', true)
                    ->first();
                    
                if ($currency) {
                    return $currencyService->formatPrice($price, $currency, $includeCode);
                }
            } catch (\Exception $e) {
                // Fallback to current currency if specific one not found
            }
        }
        
        // Use current currency if no specific one is provided or it wasn't found
        return $currencyService->formatPrice($price, null, $includeCode);
    }
}

if (!function_exists('getBasePrice')) {
    /**
     * Convert price from any currency to base currency (EGP)
     * 
     * @param float $price
     * @param string|null $fromCurrency
     * @return float
     */
    function getBasePrice($price, $fromCurrency = null)
    {
        // If no currency specified, assume price is already in base currency
        if (!$fromCurrency) {
            return $price;
        }
        
        try {
            $currency = \App\Models\Currency::where('code', $fromCurrency)->first();
            
            if ($currency) {
                // If not default currency, convert to base currency by dividing by exchange rate
                if (!$currency->is_default) {
                    return $price / $currency->rate;
                }
            }
        } catch (\Exception $e) {
            // Fallback to original price if currency not found
        }
        
        return $price;
    }
}

if (!function_exists('display_price')) {
    /**
     * HTML helper to display a formatted price with data attributes for dynamic updating
     * 
     * @param float $price Price in EGP
     * @param string|null $currencyCode
     * @return string HTML with formatted price
     */
    function display_price($price, $currencyCode = null)
    {
        // Store base price (EGP) in data attribute for JavaScript currency switching
        return '<span class="price-display" data-base-price="'.$price.'">'.format_price($price, $currencyCode).'</span>';
    }
}
