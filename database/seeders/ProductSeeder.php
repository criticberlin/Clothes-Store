<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create colors if they don't exist
        $colors = [
            ['name' => 'Red', 'hex_code' => '#FF0000'],
            ['name' => 'Blue', 'hex_code' => '#0000FF'],
            ['name' => 'Green', 'hex_code' => '#00FF00'],
            ['name' => 'Black', 'hex_code' => '#000000'],
            ['name' => 'White', 'hex_code' => '#FFFFFF'],
        ];
        
        foreach ($colors as $color) {
            Color::firstOrCreate(
                ['name' => $color['name']],
                ['hex_code' => $color['hex_code']]
            );
        }
        
        // Create sizes if they don't exist
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        
        foreach ($sizes as $size) {
            Size::firstOrCreate(['name' => $size]);
        }
        
        // Create sample products with prices in EGP
        $products = [
            [
                'code' => 'MEN-TSHIRT-001',
                'name' => 'Men\'s Basic T-Shirt',
                'price' => 999.50, // Price in EGP
                'description' => 'A comfortable cotton t-shirt for everyday wear.',
                'photo' => 'men-tshirt.jpg',
                'category' => 'men',
                'quantity' => 100,
            ],
            [
                'code' => 'MEN-JEANS-001',
                'name' => 'Men\'s Slim Fit Jeans',
                'price' => 2499.50, // Price in EGP
                'description' => 'Classic slim fit jeans for a modern look.',
                'photo' => 'men-jeans.jpg',
                'category' => 'men',
                'quantity' => 75,
            ],
            [
                'code' => 'WOMEN-DRESS-001',
                'name' => 'Women\'s Summer Dress',
                'price' => 1999.50, // Price in EGP
                'description' => 'Light and comfortable summer dress.',
                'photo' => 'women-dress.jpg',
                'category' => 'women',
                'quantity' => 50,
            ],
            [
                'code' => 'WOMEN-BLOUSE-001',
                'name' => 'Women\'s Casual Blouse',
                'price' => 1499.50, // Price in EGP
                'description' => 'Elegant blouse for casual and formal occasions.',
                'photo' => 'women-blouse.jpg',
                'category' => 'women',
                'quantity' => 60,
            ],
            [
                'code' => 'KIDS-TSHIRT-001',
                'name' => 'Kids\' Cartoon T-Shirt',
                'price' => 749.50, // Price in EGP
                'description' => 'Fun and colorful t-shirt for kids.',
                'photo' => 'kids-tshirt.jpg',
                'category' => 'kids',
                'quantity' => 80,
            ],
        ];
        
        foreach ($products as $productData) {
            $product = Product::firstOrCreate(
                ['code' => $productData['code']],
                $productData
            );
            
            // Attach random colors and sizes to each product
            $colorIds = Color::inRandomOrder()->limit(rand(1, 3))->pluck('id')->toArray();
            $sizeIds = Size::inRandomOrder()->limit(rand(2, 5))->pluck('id')->toArray();
            
            $product->colors()->sync($colorIds);
            $product->sizes()->sync($sizeIds);
        }
    }
} 