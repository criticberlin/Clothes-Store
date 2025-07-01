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

if (!function_exists('formatPrice')) {
    /**
     * Format price with the appropriate currency symbol and exchange rate
     *
     * @param float $price
     * @param string|null $currencyCode
     * @param bool $includeCode
     * @return string
     */
    function formatPrice($price, $currencyCode = null, $includeCode = false)
    {
        // Get current currency code from session or use default
        $code = $currencyCode ?? Session::get('currency_code', 'EGP');
        
        try {
            // Get currency from database
            $currency = Currency::where('code', $code)->first();
            
            if ($currency) {
                // Convert price using exchange rate if not default currency
                if (!$currency->is_default) {
                    $price = $price / $currency->exchange_rate;
                }
                
                // Format the price with the currency symbol
                $formattedPrice = $currency->symbol . number_format($price, 2);
                
                // Add currency code if requested
                if ($includeCode) {
                    $formattedPrice .= ' ' . $code;
                }
                
                return $formattedPrice;
            }
        } catch (\Exception $e) {
            // Fallback to basic formatting if currency not found
        }
        
        // Default formatting if no currency found
        return 'ج.م ' . number_format($price, 2);
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
            $currency = Currency::where('code', $fromCurrency)->first();
            
            if ($currency) {
                // If not default currency, convert to base currency
                if (!$currency->is_default) {
                    return $price * $currency->exchange_rate;
                }
            }
        } catch (\Exception $e) {
            // Fallback to original price if currency not found
        }
        
        return $price;
    }
}

if (!function_exists('displayPrice')) {
    /**
     * HTML helper to display a formatted price with data attributes for dynamic updating
     * 
     * @param float $price
     * @param string|null $currencyCode
     * @return string
     */
    function displayPrice($price, $currencyCode = null)
    {
        // Store base price (EGP) in data attribute for JavaScript currency switching
        return '<span class="price-value" data-base-price="'.$price.'">'.formatPrice($price, $currencyCode).'</span>';
    }
}
