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
                'symbol' => 'ج.م',
                'exchange_rate' => 1.00, // Base currency
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 0.020, // 1 EGP = 0.020 USD (or 1 USD = 50 EGP)
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => 0.0185, // 1 EGP = 0.0185 EUR (or 1 EUR = 54.12 EGP)
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => '£',
                'exchange_rate' => 0.0158, // 1 EGP = 0.0158 GBP (or 1 GBP = 63.29 EGP)
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'SAR',
                'name' => 'Saudi Riyal',
                'symbol' => 'ر.س',
                'exchange_rate' => 0.075, // 1 EGP = 0.075 SAR (or 1 SAR = 13.33 EGP)
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        // First, reset all defaults to ensure only one is set
        Currency::where('is_default', true)->update(['is_default' => false]);

        foreach ($currencies as $currencyData) {
            Currency::updateOrCreate(
                ['code' => $currencyData['code']],
                $currencyData
            );
        }
    }
}
