<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Role extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 
        'guard_name'
    ];
    
    /**
     * Get all permissions for the role
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
    
    /**
     * Get all users that have this role
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'model', 'model_has_roles', 'role_id', 'model_id');
    }
    
    /**
     * Assign permissions to this role
     */
    public function givePermissionTo($permissions)
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        
        $permissionIds = [];
        foreach ($permissions as $permission) {
            if (is_string($permission)) {
                $permission = Permission::where('name', $permission)->first();
            }
            
            if ($permission) {
                $permissionIds[] = $permission->id;
            }
        }
        
        return $this->permissions()->syncWithoutDetaching($permissionIds);
    }
} 