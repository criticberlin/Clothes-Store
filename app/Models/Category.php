<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'photo'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    
    /**
     * Get the image URL for this category
     */
    public function getImageUrlAttribute(): string
    {
        // Check if photo exists and is a valid path
        if ($this->photo && file_exists(public_path('storage/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }
        
        // Check if it's in the default categories directory
        if ($this->photo && file_exists(public_path('images/categories/' . $this->photo))) {
            return asset('images/categories/' . $this->photo);
        }
        
        // Default to a placeholder based on category name
        return asset('images/categories/default.jpg');
    }
}
