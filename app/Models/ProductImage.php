<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
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
    
    /**
     * Get the image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->filename) {
            if (file_exists(public_path('storage/' . $this->filename))) {
                return asset('storage/' . $this->filename);
            }
            
            if (file_exists(public_path('images/products/' . $this->filename))) {
                return asset('images/products/' . $this->filename);
            }
        }
        
        return asset('images/products/default.jpg');
    }
} 