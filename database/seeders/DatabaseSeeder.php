<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            ProductSeeder::class,
            CurrencySeeder::class,
            OrderSeeder::class,
            WishlistSeeder::class,
            SupportTicketSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
