<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class PromoCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the orders for the promo code.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope a query to only include active promo codes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                    ->orWhere('usage_count', '<', DB::raw('usage_limit'));
            });
    }

    /**
     * Calculate the discount amount for a given subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        // Check if the order meets the minimum amount requirement
        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return 0;
        }

        // Calculate the discount amount
        $discount = $this->type === 'percentage' 
            ? $subtotal * ($this->value / 100) 
            : $this->value;

        // Apply the maximum discount amount if set
        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        // Ensure the discount doesn't exceed the subtotal
        return min($discount, $subtotal);
    }

    /**
     * Increment the usage count of the promo code.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Check if the promo code is valid.
     */
    public function isValid(): bool
    {
        // Check if the promo code is active
        if (!$this->is_active) {
            return false;
        }

        // Check if the promo code has started
        if ($this->start_date && $this->start_date > now()) {
            return false;
        }

        // Check if the promo code has expired
        if ($this->end_date && $this->end_date < now()) {
            return false;
        }

        // Check if the promo code has reached its usage limit
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }
}
