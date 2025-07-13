<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Men',
                'description' => 'Men\'s clothing collection',
                'photo' => 'categories/men.jpg'
            ],
            [
                'name' => 'Women',
                'description' => 'Women\'s clothing collection',
                'photo' => 'categories/women.jpg'
            ],
            [
                'name' => 'Kids',
                'description' => 'Kids clothing collection',
                'photo' => 'categories/kids.jpg'
            ],
            [
                'name' => 'Accessories',
                'description' => 'Fashion accessories',
                'photo' => 'categories/accessories.jpg'
            ],
            [
                'name' => 'Footwear',
                'description' => 'Shoes and footwear',
                'photo' => 'categories/footwear.jpg'
            ]
        ];

        foreach ($categories as $category) {
            $category['slug'] = Str::slug($category['name']);
            Category::firstOrCreate(['name' => $category['name']], $category);
        }

        // Assign random categories to existing products
        $products = Product::all();
        $categoryIds = Category::pluck('id')->toArray();

        foreach ($products as $product) {
            // Get 1-2 random category IDs
            $randomCategoryIds = array_rand(array_flip($categoryIds), rand(1, 2));
            
            // If only one category is selected, convert to array
            if (!is_array($randomCategoryIds)) {
                $randomCategoryIds = [$randomCategoryIds];
            }
            
            $product->categories()->sync($randomCategoryIds);
        }
    }
}
