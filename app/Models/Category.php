<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'photo',
        'parent_id',
        'type',
        'status'
    ];

    /**
     * Get all products associated with this category
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    
    /**
     * Get the parent category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    /**
     * Get all parent categories (many-to-many)
     */
    public function parents()
    {
        return $this->belongsToMany(
            Category::class, 
            'category_category', 
            'child_id', 
            'parent_id'
        );
    }
    
    /**
     * Get the child categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    
    /**
     * Get all child categories (many-to-many)
     */
    public function allChildren()
    {
        return $this->belongsToMany(
            Category::class, 
            'category_category', 
            'parent_id', 
            'child_id'
        );
    }
    
    /**
     * Check if category has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0 || $this->allChildren()->count() > 0;
    }
    
    /**
     * Get all available parent categories for this category
     * (excludes self and descendants to prevent circular references)
     */
    public function getAvailableParents()
    {
        // If this is a new record, all categories are available
        if (!$this->exists) {
            return Category::where('id', '!=', $this->id ?? 0)->get();
        }
        
        // Get all descendant IDs to exclude (to prevent circular references)
        $excludeIds = $this->getAllChildrenIds();
        $excludeIds[] = $this->id;
        
        return Category::whereNotIn('id', $excludeIds)->get();
    }
    
    /**
     * Get all children IDs recursively
     */
    protected function getAllChildrenIds(): array
    {
        $ids = [];
        
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllChildrenIds());
        }
        
        return $ids;
    }
    
    /**
     * Get ancestors of this category
     */
    public function getAncestors()
    {
        // Get direct ancestors through parent_id
        $ancestors = collect([]);
        $category = $this;
        
        while ($category->parent) {
            $ancestors->push($category->parent);
            $category = $category->parent;
        }
        
        // Add many-to-many parents
        $this->parents->each(function($parent) use ($ancestors) {
            if (!$ancestors->contains('id', $parent->id)) {
                $ancestors->push($parent);
            }
        });
        
        return $ancestors->sortBy('name');
    }
    
    /**
     * Get category breadcrumb path
     */
    public function getBreadcrumbPath(): string
    {
        $ancestors = $this->getAncestors();
        
        if ($ancestors->isEmpty()) {
            return $this->name;
        }
        
        $parentNames = $ancestors->pluck('name')->implode(', ');
        return $parentNames . ' > ' . $this->name;
    }
    
    /**
     * Get categories filtered by type
     */
    public static function getByType(string $type)
    {
        return self::where('type', $type)->where('status', true)->get();
    }
    
    /**
     * Get main categories (top level)
     */
    public static function getMainCategories()
    {
        return self::where('type', 'main')
            ->where('parent_id', null)
            ->where('status', true)
            ->get();
    }
    
    /**
     * Get visible categories for store frontend
     * This ensures only active categories are displayed
     */
    public static function getVisibleCategories()
    {
        return self::where('status', true)->get();
    }
    
    /**
     * Scope a query to only include visible categories
     */
    public function scopeVisible($query)
    {
        return $query->where('status', true);
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
