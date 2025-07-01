<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $adminEmail = 'admin@example.com';
        $adminUser = User::where('email', $adminEmail)->first();
        
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]);
        }
        
        // Get or create admin role
        $adminRole = Role::where('name', 'Admin')->first();
        if (!$adminRole) {
            $adminRole = Role::create([
                'name' => 'Admin',
                'guard_name' => 'web'
            ]);
        }
        
        // Assign admin role to admin user
        $hasRole = DB::table('model_has_roles')
            ->where('role_id', $adminRole->id)
            ->where('model_id', $adminUser->id)
            ->where('model_type', 'App\\Models\\User')
            ->exists();
            
        if (!$hasRole) {
            DB::table('model_has_roles')->insert([
                'role_id' => $adminRole->id,
                'model_type' => 'App\\Models\\User',
                'model_id' => $adminUser->id
            ]);
        }
        
        // Create all necessary permissions
        $permissions = [
            'view_users', 'edit_users', 'delete_users', 'change_password',
            'manage_orders', 'Complaints', 'admin_dashboard', 'manage_products',
            'view_products', 'edit_products', 'delete_products',
            'view_orders', 'edit_orders', 'delete_orders',
            'view_customers', 'edit_customers', 'delete_customers',
            'view_reports', 'view_settings', 'edit_settings',
            'view_support', 'reply_support', 'close_support'
        ];
        
        foreach ($permissions as $permName) {
            $permission = Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'web'
            ]);
            
            // Assign permission to admin role
            $hasPermission = DB::table('role_has_permissions')
                ->where('permission_id', $permission->id)
                ->where('role_id', $adminRole->id)
                ->exists();
                
            if (!$hasPermission) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permission->id,
                    'role_id' => $adminRole->id
                ]);
            }
            
            // Assign permission to admin user directly
            $userHasPermission = DB::table('model_has_permissions')
                ->where('permission_id', $permission->id)
                ->where('model_id', $adminUser->id)
                ->where('model_type', 'App\\Models\\User')
                ->exists();
                
            if (!$userHasPermission) {
                DB::table('model_has_permissions')->insert([
                    'permission_id' => $permission->id,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $adminUser->id
                ]);
            }
        }
        
        $this->command->info('Admin user has been set up with all permissions!');
    }
} 