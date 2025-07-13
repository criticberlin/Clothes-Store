<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'address',
        'profile_photo',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Get all roles for the user
     */
    public function roles()
    {
        return $this->morphToMany('App\Models\Role', 'model', 'model_has_roles', 'model_id', 'role_id');
    }
    
    /**
     * Get all permissions for the user
     */
    public function permissions()
    {
        return $this->morphToMany('App\Models\Permission', 'model', 'model_has_permissions', 'model_id', 'permission_id');
    }
    
    /**
     * Check if user has a specific permission
     * 
     * @param string $permission
     * @return bool
     */
    public function hasPermissionTo($permission)
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
     * Get the URL for the user's profile photo
     * 
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        
        return asset('images/default-avatar.png');
    }
    
    /**
     * Get all ratings submitted by this user
     */
    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }
}
