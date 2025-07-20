<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol_en' => '$',
                'symbol_ar' => '$',
                'rate' => 1.0,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol_en' => '€',
                'symbol_ar' => '€',
                'rate' => 0.92,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol_en' => '£',
                'symbol_ar' => '£',
                'rate' => 0.79,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'JPY',
                'name' => 'Japanese Yen',
                'symbol_en' => '¥',
                'symbol_ar' => '¥',
                'rate' => 150.25,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'EGP',
                'name' => 'Egyptian Pound',
                'symbol_en' => 'EGP',
                'symbol_ar' => 'ج.م',
                'rate' => 30.90,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'SAR',
                'name' => 'Saudi Riyal',
                'symbol_en' => 'SAR',
                'symbol_ar' => 'ر.س',
                'rate' => 3.75,
                'is_default' => false,
                'is_active' => true,
            ],
        ];
        
        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
 