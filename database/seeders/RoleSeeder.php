<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if roles already exist
        if (DB::table('roles')->count() === 0) {
            // Create roles
            $roles = [
                ['name' => 'admin', 'guard_name' => 'web'],
                ['name' => 'customer', 'guard_name' => 'web'],
                ['name' => 'manager', 'guard_name' => 'web']
            ];

            foreach ($roles as $role) {
                DB::table('roles')->insert([
                    'name' => $role['name'],
                    'guard_name' => $role['guard_name'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Log success
            $this->command->info('Roles created successfully!');
        } else {
            $this->command->info('Roles already exist, skipping...');
        }
    }
} 