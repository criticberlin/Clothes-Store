<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'address' => '123 Admin Street, Admin City',
                'phone' => '+1234567890',
            ]
        );
        
        // Create manager user if doesn't exist
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'address' => '456 Manager Avenue, Manager Town',
                'phone' => '+1987654321',
            ]
        );
        
        // Create customer user if doesn't exist
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'address' => '789 Customer Road, Customer City',
                'phone' => '+1122334455',
            ]
        );

        // Get role IDs
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $managerRoleId = DB::table('roles')->where('name', 'manager')->value('id');
        $customerRoleId = DB::table('roles')->where('name', 'customer')->value('id');

        // Assign roles to users (if not already assigned)
        $this->assignRoleIfNotExists($admin->id, $adminRoleId);
        $this->assignRoleIfNotExists($manager->id, $managerRoleId);
        $this->assignRoleIfNotExists($customer->id, $customerRoleId);

        // Create additional customers if needed
        for ($i = 0; $i < 10; $i++) {
            $email = 'customer' . ($i + 1) . '@example.com';
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Customer ' . ($i + 1),
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'address' => ($i + 100) . ' Customer Street, Customer City',
                    'phone' => '+1' . rand(1000000000, 9999999999),
                ]
            );
            
            $this->assignRoleIfNotExists($user->id, $customerRoleId);
        }
    }
    
    /**
     * Assign a role to a user if not already assigned
     */
    private function assignRoleIfNotExists($userId, $roleId): void
    {
        $exists = DB::table('model_has_roles')
            ->where('role_id', $roleId)
            ->where('model_id', $userId)
            ->where('model_type', 'App\\Models\\User')
            ->exists();
            
        if (!$exists) {
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => 'App\\Models\\User',
                'model_id' => $userId
            ]);
        }
    }
} 