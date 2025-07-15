<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default currencies with exchange rates
        $currencies = [
            [
                'code' => 'EGP',
                'name' => 'Egyptian Pound',
                'symbol_en' => 'EGP',
                'symbol_ar' => 'ج.م',
                'rate' => 1.00, // Base currency (in EGP)
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol_en' => '$',
                'symbol_ar' => 'دولار',
                'rate' => 0.02, // Based on 1 USD = 50 EGP
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol_en' => '€',
                'symbol_ar' => 'يورو',
                'rate' => 0.0174, // Based on 1 EUR = 0.87 USD (and 1 USD = 50 EGP)
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol_en' => '£',
                'symbol_ar' => 'جنيه',
                'rate' => 0.015, // Based on 1 GBP = 0.75 USD (and 1 USD = 50 EGP)
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'SAR',
                'name' => 'Saudi Riyal',
                'symbol_en' => 'SAR',
                'symbol_ar' => 'ر.س',
                'rate' => 0.0758, // Based on 1 SAR = 3.79 USD (and 1 USD = 50 EGP)
                'is_default' => false,
                'is_active' => true,
            ]
        ];
        
        foreach ($currencies as $currencyData) {
            Currency::updateOrCreate(
                ['code' => $currencyData['code']],
                $currencyData
            );
        }
        
        $this->command->info('Currencies seeded successfully!');
    }
}
 