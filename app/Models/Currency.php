<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

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
        'symbol_en',
        'symbol_ar',
        'rate',
        'is_default',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'float',
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
     * Get the appropriate symbol based on current locale
     * 
     * @return string
     */
    public function getSymbolForCurrentLocale()
    {
        // Use arabic symbol if locale is Arabic
        if (App::getLocale() === 'ar') {
            return $this->symbol_ar;
        }
        
        return $this->symbol_en;
    }
} 