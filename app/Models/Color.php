<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "name",
        "hex_code"
    ];

    /**
     * Get all products with this color
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
    
    /**
     * Get the CSS background color style for this color
     */
    public function getColorStyleAttribute(): string
    {
        return "background-color: {$this->hex_code};";
    }
}
