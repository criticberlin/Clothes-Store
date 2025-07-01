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

if (!function_exists('format_price')) {
    /**
     * Format a price with the current or specified currency
     *
     * @param float $price The price to format
     * @param string|null $currencyCode Optional currency code to use
     * @param bool $includeCode Whether to include the currency code
     * @return string
     */
    function format_price($price, $currencyCode = null, $includeCode = false)
    {
        // Get current currency code from session or use default
        $code = $currencyCode ?? Session::get('currency_code', 'EGP');
        
        try {
            // Get currency from database
            $currency = Currency::where('code', $code)->first();
            
            if ($currency) {
                // Convert price using exchange rate if not default currency
                if (!$currency->is_default) {
                    $price = $price * $currency->exchange_rate;
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
        return 'EGP ' . number_format($price, 2);
    }
}
