<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model {
    protected $table = "products";
    protected $fillable = [
        'code',
        'name',
        'price',
        'description',
        'photo',
        'image_path',
        'category',
        'quantity',
        'created_by',
    ];

    public function colors(): BelongsToMany {
        return $this->belongsToMany(Color::class);
    }

    public function sizes(): BelongsToMany {
        return $this->belongsToMany(Size::class, 'product_size', 'product_id', 'size_id');
    }
    
    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class);
    }
    
    /**
     * Get all ratings for this product
     */
    public function ratings(): HasMany {
        return $this->hasMany(ProductRating::class);
    }
    
    /**
     * Calculate the average rating for this product
     */
    public function getAverageRatingAttribute(): float {
        return $this->ratings()->where('is_approved', true)->avg('rating') ?? 0;
    }
    
    /**
     * Get the total number of ratings for this product
     */
    public function getRatingsCountAttribute(): int {
        return $this->ratings()->where('is_approved', true)->count();
    }
}