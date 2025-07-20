<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'change_password',
            
            // Product management
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            
            // Category management
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',
            
            // Order management
            'view_orders',
            'update_orders',
            'delete_orders',
            
            // Support tickets
            'view_tickets',
            'reply_tickets',
            'close_tickets',
            
            // Dashboard access
            'admin_dashboard',
            'customer_dashboard',
            
            // Settings
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create roles
        $roles = ['admin', 'customer', 'manager'];
        
        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore([
                'name' => $role,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Get role IDs
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $customerRoleId = DB::table('roles')->where('name', 'customer')->value('id');
        $managerRoleId = DB::table('roles')->where('name', 'manager')->value('id');

        // Get permission IDs
        $allPermissionIds = DB::table('permissions')->pluck('id')->toArray();
        $customerPermissionIds = DB::table('permissions')
            ->whereIn('name', ['customer_dashboard', 'view_products', 'view_categories'])
            ->pluck('id')
            ->toArray();
        $managerPermissionIds = DB::table('permissions')
            ->whereIn('name', [
                'admin_dashboard', 'view_products', 'create_products', 'edit_products',
                'view_categories', 'create_categories', 'edit_categories',
                'view_orders', 'update_orders', 'view_tickets', 'reply_tickets'
            ])
            ->pluck('id')
            ->toArray();

        // Assign permissions to roles
        // Admin gets all permissions
        foreach ($allPermissionIds as $permissionId) {
            DB::table('role_has_permissions')->insertOrIgnore([
                'permission_id' => $permissionId,
                'role_id' => $adminRoleId
            ]);
        }

        // Customer gets limited permissions
        foreach ($customerPermissionIds as $permissionId) {
            DB::table('role_has_permissions')->insertOrIgnore([
                'permission_id' => $permissionId,
                'role_id' => $customerRoleId
            ]);
        }

        // Manager gets more permissions
        foreach ($managerPermissionIds as $permissionId) {
            DB::table('role_has_permissions')->insertOrIgnore([
                'permission_id' => $permissionId,
                'role_id' => $managerRoleId
            ]);
        }
    }
} 