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
        // Create an admin user
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Create test user
        $userId = DB::table('users')->insertGetId([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Create roles
        $adminRoleId = DB::table('roles')->insertGetId([
            'name' => 'admin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $customerRoleId = DB::table('roles')->insertGetId([
            'name' => 'customer',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Create permissions
        $permissions = [
            'view_users', 'edit_users', 'delete_users', 'change_password',
            'manage_orders', 'Complaints'
        ];
        
        foreach ($permissions as $permName) {
            DB::table('permissions')->insert([
                'name' => $permName,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Assign admin role to admin user
        DB::table('model_has_roles')->insert([
            'role_id' => $adminRoleId,
            'model_type' => 'App\\Models\\User',
            'model_id' => $adminId
        ]);
        
        // Assign customer role to test user
        DB::table('model_has_roles')->insert([
            'role_id' => $customerRoleId,
            'model_type' => 'App\\Models\\User',
            'model_id' => $userId
        ]);
        
        $this->command->info('Database seeded with users, roles and permissions!');

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
