<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Convert a price from base currency (EGP) to the target currency
     */
    public function convert(float $amount): float
    {
        return $amount / $this->exchange_rate;
    }

    /**
     * Convert a price from any currency back to base currency (EGP)
     */
    public function convertToBase(float $amount): float
    {
        return $amount * $this->exchange_rate;
    }

    /**
     * Format a price with the currency symbol
     */
    public function format(float $amount, bool $convertFromBase = false, bool $includeCode = false): string
    {
        // If needed, convert from base currency to this currency
        if ($convertFromBase && !$this->is_default) {
            $amount = $amount * $this->exchange_rate;
        }
        
        $formatted = $this->symbol . number_format($amount, 2);
        
        if ($includeCode) {
            $formatted .= ' ' . $this->code;
        }
        
        return $formatted;
    }

    /**
     * Get the default currency
     */
    public static function getDefault()
    {
        return Cache::remember('default_currency', 60 * 24, function () {
            return self::where('is_default', true)->first() ?? self::first();
        });
    }
}
