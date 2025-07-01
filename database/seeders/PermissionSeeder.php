<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if permissions already exist
        if (DB::table('permissions')->count() === 0) {
            // Create permissions
            $permissions = [
                ['name' => 'admin_dashboard', 'guard_name' => 'web'],
                ['name' => 'manage_users', 'guard_name' => 'web'],
                ['name' => 'manage_products', 'guard_name' => 'web'],
                ['name' => 'manage_orders', 'guard_name' => 'web'],
                ['name' => 'Complaints', 'guard_name' => 'web'],
            ];

            foreach ($permissions as $permission) {
                DB::table('permissions')->insert([
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Log success
            $this->command->info('Permissions created successfully!');
        } else {
            $this->command->info('Permissions already exist, skipping...');
        }
        
        // Assign all permissions to admin role
        $adminRoleId = DB::table('roles')->where('name', 'Admin')->value('id');
        $permissions = DB::table('permissions')->get();
        
        foreach ($permissions as $permission) {
            // Check if the role-permission assignment already exists
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
        
        $this->command->info('Admin permissions assigned successfully!');
    }
} 