<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'address',
        'profile_photo',
        'phone',
        'social_id',
        'social_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        'balance' => 'decimal:2',
        ];
    
    /**
     * Get all roles for the user
     */
    public function roles(): MorphToMany
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles', 'model_id', 'role_id');
    }
    
    /**
     * Get all permissions for the user
     */
    public function permissions(): MorphToMany
    {
        return $this->morphToMany(Permission::class, 'model', 'model_has_permissions', 'model_id', 'permission_id');
    }
    
    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }
    
    /**
     * Check if user has a specific permission
     */
    public function hasPermissionTo(string $permission): bool
    {
        // Check direct permissions
        if ($this->permissions->contains('name', $permission)) {
            return true;
        }
        
        // Check role permissions
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get all products created by this user
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'created_by');
    }
    
    /**
     * Get all ratings submitted by this user
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(ProductRating::class);
    }
    
    /**
     * Get all cart items for this user
     */
    public function cart(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
    
    /**
     * Get all wishlist items for this user
     */
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    
    /**
     * Get all orders for this user
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    
    /**
     * Get all support tickets created by this user
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }
    
    /**
     * Check if user has a product in their wishlist
     */
    public function hasInWishlist(int $productId): bool
    {
        return $this->wishlist()->where('product_id', $productId)->exists();
    }
    
    /**
     * Get the URL for the user's profile photo
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            // Check if it's a storage path
            if (file_exists(public_path('storage/' . $this->profile_photo))) {
                return asset('storage/' . $this->profile_photo);
            }
            
            // Check if it's in the users directory
            if (file_exists(public_path('images/users/' . $this->profile_photo))) {
                return asset('images/users/' . $this->profile_photo);
            }
        }
        
        // Default avatar
        return asset('images/default-avatar.png');
    }
}
