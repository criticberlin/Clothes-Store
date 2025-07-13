<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an admin user if it doesn't exist
        $adminEmail = 'admin@example.com';
        $adminUser = User::where('email', $adminEmail)->first();
        
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]);
            $adminId = $adminUser->id;
        } else {
            $adminId = $adminUser->id;
        }
        
        // Create test user if it doesn't exist
        $testEmail = 'test@example.com';
        $testUser = User::where('email', $testEmail)->first();
        
        if (!$testUser) {
            $testUser = User::create([
                'name' => 'Test User',
                'email' => $testEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]);
            $userId = $testUser->id;
        } else {
            $userId = $testUser->id;
        }
        
        // Create roles if they don't exist using DB query to avoid model dependency
        $adminRoleExists = DB::table('roles')->where('name', 'Admin')->exists();
        if (!$adminRoleExists) {
            // Check if there's an 'admin' role that needs to be updated
            $oldAdminRole = DB::table('roles')->where('name', 'admin')->first();
            
            if ($oldAdminRole) {
                // Update the existing role
                DB::table('roles')
                    ->where('id', $oldAdminRole->id)
                    ->update([
                        'name' => 'Admin',
                        'updated_at' => now()
                    ]);
                $adminRoleId = $oldAdminRole->id;
            } else {
                // Create a new role
                $adminRoleId = DB::table('roles')->insertGetId([
                    'name' => 'Admin',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } else {
            $adminRoleId = DB::table('roles')->where('name', 'Admin')->value('id');
        }
        
        $customerRoleExists = DB::table('roles')->where('name', 'customer')->exists();
        if (!$customerRoleExists) {
            $customerRoleId = DB::table('roles')->insertGetId([
                'name' => 'customer',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $customerRoleId = DB::table('roles')->where('name', 'customer')->value('id');
        }
        
        // Create permissions if they don't exist
        $permissions = [
            'view_users', 'edit_users', 'delete_users', 'change_password',
            'manage_orders', 'Complaints', 'admin_dashboard', 'manage_products'
        ];
        
        foreach ($permissions as $permName) {
            $permExists = DB::table('permissions')->where('name', $permName)->exists();
            if (!$permExists) {
                DB::table('permissions')->insert([
                    'name' => $permName,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        // Assign admin role to admin user if not already assigned
        $adminHasRole = DB::table('model_has_roles')
            ->where('role_id', $adminRoleId)
            ->where('model_id', $adminId)
            ->where('model_type', 'App\\Models\\User')
            ->exists();
            
        if (!$adminHasRole) {
            DB::table('model_has_roles')->insert([
                'role_id' => $adminRoleId,
                'model_type' => 'App\\Models\\User',
                'model_id' => $adminId
            ]);
        }
        
        // Assign customer role to test user if not already assigned
        $userHasRole = DB::table('model_has_roles')
            ->where('role_id', $customerRoleId)
            ->where('model_id', $userId)
            ->where('model_type', 'App\\Models\\User')
            ->exists();
            
        if (!$userHasRole) {
            DB::table('model_has_roles')->insert([
                'role_id' => $customerRoleId,
                'model_type' => 'App\\Models\\User',
                'model_id' => $userId
            ]);
        }
        
        // Assign all permissions to admin role
        $adminPermissions = DB::table('permissions')->get();
        
        foreach ($adminPermissions as $permission) {
            $exists = DB::table('role_has_permissions')
                ->where('permission_id', $permission->id)
                ->where('role_id', $adminRoleId)
                ->exists();
                
            if (!$exists) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permission->id,
                    'role_id' => $adminRoleId
                ]);
            }
        }
        
        // Assign permissions to admin user directly
        $adminDashboardPermId = DB::table('permissions')->where('name', 'admin_dashboard')->value('id');
        if ($adminDashboardPermId) {
            $exists = DB::table('model_has_permissions')
                ->where('permission_id', $adminDashboardPermId)
                ->where('model_id', $adminId)
                ->where('model_type', 'App\\Models\\User')
                ->exists();
                
            if (!$exists) {
                DB::table('model_has_permissions')->insert([
                    'permission_id' => $adminDashboardPermId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $adminId
                ]);
            }
        }
        
        $this->command->info('Database seeded with users, roles and permissions!');

        // Run the AdminUserSeeder to ensure admin has all required permissions
        $this->call([
            AdminUserSeeder::class,
            ProductSeeder::class,
            CategorySeeder::class,
            CurrencySeeder::class,
        ]);
        
        // Create the storage link if it doesn't exist
        if (!file_exists(public_path('storage'))) {
            $this->command->call('storage:link');
        }
        
        // Copy sample images to storage
        $this->command->call('images:copy-samples');
    }
}
