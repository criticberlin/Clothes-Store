<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'cost',
        'estimated_days',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cost' => 'decimal:2',
        'estimated_days' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the orders for the shipping method.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope a query to only include active shipping methods.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the estimated delivery time as a string.
     */
    public function getEstimatedDeliveryTimeAttribute(): string
    {
        if ($this->estimated_days === 0) {
            return __('Same day delivery');
        }

        if ($this->estimated_days === 1) {
            return __('Next day delivery');
        }

        return __(':days business days', ['days' => $this->estimated_days]);
    }
}
