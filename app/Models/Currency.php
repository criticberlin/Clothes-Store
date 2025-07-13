<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'exchange_rate' => 'float',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get all active currencies.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveCurrencies()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Get the default currency.
     *
     * @return \App\Models\Currency|null
     */
    public static function getDefaultCurrency()
    {
        return self::where('is_default', true)->first();
    }

    /**
     * Convert an amount from the base currency to this currency.
     *
     * @param float $amount
     * @return float
     */
    public function convert($amount)
    {
        return $amount * $this->exchange_rate;
    }

    /**
     * Format an amount in this currency.
     *
     * @param float $amount
     * @param bool $includeSymbol
     * @return string
     */
    public function format($amount, $includeSymbol = true)
    {
        $formattedAmount = number_format($this->convert($amount), 2);
        
        return $includeSymbol ? $this->symbol . $formattedAmount : $formattedAmount;
    }
}
