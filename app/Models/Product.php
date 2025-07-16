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
     * Get all images for this product
     */
    public function images(): HasMany {
        return $this->hasMany(ProductImage::class);
    }
    
    /**
     * Get products recommended for this product
     */
    public function recommendedProducts(): BelongsToMany {
        return $this->belongsToMany(
            Product::class,
            'product_recommendations',
            'product_id',
            'recommended_product_id'
        )->withPivot('sort_order')->orderBy('sort_order');
    }
    
    /**
     * Get products that recommend this product
     */
    public function recommendedBy(): BelongsToMany {
        return $this->belongsToMany(
            Product::class,
            'product_recommendations',
            'recommended_product_id',
            'product_id'
        );
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
    
    /**
     * Get the image URL for this product
     */
    public function getImageUrlAttribute(): string {
        // First try image_path (new field)
        if ($this->image_path && file_exists(public_path('storage/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }
        
        // Then try photo (old field)
        if ($this->photo) {
            // Check if it's a full path or just a filename
            if (str_starts_with($this->photo, 'products/') && file_exists(public_path('storage/' . $this->photo))) {
                return asset('storage/' . $this->photo);
            }
            
            // Check if it's in the default products directory
            if (file_exists(public_path('images/products/' . $this->photo))) {
                return asset('images/products/' . $this->photo);
            }
        }
        
        // Default image
        return asset('images/products/default.jpg');
    }
    
    /**
     * Get the thumbnail URL for this product
     */
    public function getThumbnailUrlAttribute(): string {
        // Similar logic as getImageUrlAttribute but for thumbnails
        // First try image_path (new field)
        if ($this->image_path && file_exists(public_path('storage/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }
        
        // Then try photo (old field)
        if ($this->photo) {
            // Check if it's a full path or just a filename
            if (str_starts_with($this->photo, 'products/') && file_exists(public_path('storage/' . $this->photo))) {
                return asset('storage/' . $this->photo);
            }
            
            // Check if it's in the default products directory
            if (file_exists(public_path('images/products/' . $this->photo))) {
                return asset('images/products/' . $this->photo);
            }
        }
        
        // Default thumbnail
        return asset('images/products/default-thumbnail.jpg');
    }
}