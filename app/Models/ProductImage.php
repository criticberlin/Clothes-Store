<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'filename',
        'sort_order'
    ];

    /**
     * Get the product that owns the image.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
} 