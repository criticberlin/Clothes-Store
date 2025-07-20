<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Clear existing payment methods
        PaymentMethod::truncate();
        
        try {
            // Create cash on delivery payment method
            PaymentMethod::create([
                'name' => 'Cash on Delivery',
                'code' => 'cod',
                'description' => 'Pay with cash when your order is delivered',
                'icon' => 'cash.svg',
                'fee' => 10.00,
                'is_active' => true,
            ]);
            
            // Create credit card payment method
            PaymentMethod::create([
                'name' => 'Credit/Debit Card',
                'code' => 'card',
                'description' => 'Pay securely with your credit or debit card',
                'icon' => 'card.svg',
                'fee' => 0.00,
                'is_active' => true,
                'config' => json_encode([
                    'gateway' => 'stripe',
                    'test_mode' => true,
                ]),
            ]);
            
            // Create buy now pay later payment method
            PaymentMethod::create([
                'name' => 'Buy Now Pay Later',
                'code' => 'bnpl',
                'description' => 'Pay in installments with ValU',
                'icon' => 'valu.svg',
                'fee' => 0.00,
                'is_active' => true,
                'config' => json_encode([
                    'gateway' => 'valu',
                    'test_mode' => true,
                ]),
            ]);
            
            $this->command->info('Payment methods seeded successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error seeding payment methods: ' . $e->getMessage());
        }
        
        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}

        // Create cash on delivery payment method
        PaymentMethod::create([
            'name' => 'Cash on Delivery',
            'code' => 'cod',
            'description' => 'Pay with cash when your order is delivered',
            'icon' => 'cash.svg',
            'fee' => 10.00,
            'is_active' => true,
        ]);
        
        // Create credit card payment method
        PaymentMethod::create([
            'name' => 'Credit/Debit Card',
            'code' => 'card',
            'description' => 'Pay securely with your credit or debit card',
            'icon' => 'card.svg',
            'fee' => 0.00,
            'is_active' => true,
            'config' => json_encode([
                'gateway' => 'stripe',
                'test_mode' => true,
            ]),
        ]);
        
        // Create buy now pay later payment method
        PaymentMethod::create([
            'name' => 'Buy Now Pay Later',
            'code' => 'bnpl',
            'description' => 'Pay in installments with ValU',
            'icon' => 'valu.svg',
            'fee' => 0.00,
            'is_active' => true,
            'config' => json_encode([
                'gateway' => 'valu',
                'test_mode' => true,
            ]),
        ]);
        
        $this->command->info('Payment methods seeded successfully!');
    
