<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Create some colors
        $colors = [
            ['name' => 'Red', 'hex_code' => '#FF0000'],
            ['name' => 'Blue', 'hex_code' => '#0000FF'],
            ['name' => 'Green', 'hex_code' => '#00FF00'],
            ['name' => 'Black', 'hex_code' => '#000000'],
            ['name' => 'White', 'hex_code' => '#FFFFFF'],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }

        // Create some sizes
        $sizes = [
            ['name' => 'S'],
            ['name' => 'M'],
            ['name' => 'L'],
            ['name' => 'XL'],
            ['name' => 'XXL'],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }

        // Create some products
        $products = [
            [
                'code' => 'M001',
                'name' => 'Men\'s T-Shirt',
                'price' => 29.99,
                'description' => 'Comfortable cotton t-shirt for men',
                'photo' => 'men-tshirt.jpg',
                'category' => 'men',
                'quantity' => 100
            ],
            [
                'code' => 'M002',
                'name' => 'Men\'s Jeans',
                'price' => 49.99,
                'description' => 'Stylish denim jeans for men',
                'photo' => 'men-jeans.jpg',
                'category' => 'men',
                'quantity' => 80
            ],
            [
                'code' => 'W001',
                'name' => 'Women\'s Blouse',
                'price' => 39.99,
                'description' => 'Elegant blouse for women',
                'photo' => 'women-blouse.jpg',
                'category' => 'women',
                'quantity' => 90
            ],
            [
                'code' => 'W002',
                'name' => 'Women\'s Skirt',
                'price' => 45.99,
                'description' => 'Fashionable skirt for women',
                'photo' => 'women-skirt.jpg',
                'category' => 'women',
                'quantity' => 75
            ],
            [
                'code' => 'K001',
                'name' => 'Kid\'s T-Shirt',
                'price' => 19.99,
                'description' => 'Colorful t-shirt for kids',
                'photo' => 'kid-tshirt.jpg',
                'category' => 'kids',
                'quantity' => 120
            ],
            [
                'code' => 'K002',
                'name' => 'Baby Romper',
                'price' => 24.99,
                'description' => 'Cute romper for babies',
                'photo' => 'baby-romper.jpg',
                'category' => 'kids',
                'quantity' => 110
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);
            
            // Attach random colors to each product
            $colorIds = Color::inRandomOrder()->take(rand(1, 3))->pluck('id')->toArray();
            $product->colors()->attach($colorIds);
            
            // Attach random sizes to each product
            $sizeIds = Size::inRandomOrder()->take(rand(1, 4))->pluck('id')->toArray();
            $product->sizes()->attach($sizeIds);
        }
    }
} 