<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Clothes Store',
                'group' => 'general',
            ],
            [
                'key' => 'site_description',
                'value' => 'Your one-stop shop for fashion and clothing.',
                'group' => 'general',
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@clothesstore.com',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1 (123) 456-7890',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_address',
                'value' => '123 Fashion Street, Style City, SC 12345',
                'group' => 'contact',
            ],
            [
                'key' => 'facebook_link',
                'value' => 'https://facebook.com/clothesstore',
                'group' => 'social',
            ],
            [
                'key' => 'instagram_link',
                'value' => 'https://instagram.com/clothesstore',
                'group' => 'social',
            ],
            [
                'key' => 'twitter_link',
                'value' => 'https://twitter.com/clothesstore',
                'group' => 'social',
            ],
            [
                'key' => 'shipping_cost',
                'value' => '5.99',
                'group' => 'shipping',
            ],
            [
                'key' => 'free_shipping_threshold',
                'value' => '50',
                'group' => 'shipping',
            ],
            [
                'key' => 'tax_rate',
                'value' => '7.5',
                'group' => 'tax',
            ],
            [
                'key' => 'return_policy',
                'value' => 'Items can be returned within 30 days of purchase.',
                'group' => 'policy',
            ],
        ];

        // Create settings table if it doesn't exist
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('group')->default('general');
                $table->timestamps();
            });
        }

        // Insert settings
        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
} 