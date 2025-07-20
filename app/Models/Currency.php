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
     */
    protected $casts = [
        'rate' => 'float',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active currencies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include the default currency
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get the appropriate symbol based on current locale
     */
    public function getSymbolForCurrentLocale(): string
    {
        // Use arabic symbol if locale is Arabic
        if (App::getLocale() === 'ar') {
            return $this->symbol_ar;
        }
        
        return $this->symbol_en;
    }

    /**
     * Format a price in this currency
     */
    public function formatPrice(float $price): string
    {
        $symbol = $this->getSymbolForCurrentLocale();
        
        if (App::getLocale() === 'ar') {
            return number_format($price, 2) . ' ' . $symbol;
        }
        
        return $symbol . ' ' . number_format($price, 2);
    }
} 