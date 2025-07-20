<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'governorate_id',
        'city_id',
        'full_name',
        'mobile_number',
        'street_address',
        'building_number',
        'floor_number',
        'apartment_number',
        'delivery_instructions',
        'is_default',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the governorate that owns the address.
     */
    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    /**
     * Get the city that owns the address.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the orders for the address.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the full address as a string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->street_address,
        ];

        if ($this->building_number) {
            $parts[] = __('Building') . ': ' . $this->building_number;
        }

        if ($this->floor_number) {
            $parts[] = __('Floor') . ': ' . $this->floor_number;
        }

        if ($this->apartment_number) {
            $parts[] = __('Apartment') . ': ' . $this->apartment_number;
        }

        $parts[] = $this->city->name;
        $parts[] = $this->governorate->name;

        return implode(', ', $parts);
    }
}
