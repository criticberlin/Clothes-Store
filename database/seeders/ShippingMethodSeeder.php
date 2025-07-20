<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Clear existing shipping methods
        ShippingMethod::truncate();
        
        try {
            // Create standard shipping method
            ShippingMethod::create([
                'name' => 'Standard Shipping',
                'code' => 'standard',
                'description' => 'Delivery within 2-3 business days',
                'cost' => 50.00,
                'estimated_days' => 3,
                'is_active' => true,
            ]);
            
            // Create express shipping method
            ShippingMethod::create([
                'name' => 'Express Shipping',
                'code' => 'express',
                'description' => 'Same-day delivery (order before 2 PM)',
                'cost' => 100.00,
                'estimated_days' => 0,
                'is_active' => true,
            ]);
            
            $this->command->info('Shipping methods seeded successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error seeding shipping methods: ' . $e->getMessage());
        }
        
        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}
