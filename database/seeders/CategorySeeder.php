<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main categories
        $mainCategories = [
            [
                'name' => 'Men',
                'type' => 'main',
                'children' => [
                    ['name' => 'T-Shirts', 'type' => 'clothing'],
                    ['name' => 'Shirts', 'type' => 'clothing'],
                    ['name' => 'Pants', 'type' => 'clothing'],
                    ['name' => 'Men Jeans', 'type' => 'clothing'],
                    ['name' => 'Men Jackets', 'type' => 'clothing'],
                ],
            ],
            [
                'name' => 'Women',
                'type' => 'main',
                'children' => [
                    ['name' => 'Dresses', 'type' => 'clothing'],
                    ['name' => 'Tops', 'type' => 'clothing'],
                    ['name' => 'Skirts', 'type' => 'clothing'],
                    ['name' => 'Women Jeans', 'type' => 'clothing'],
                    ['name' => 'Women Jackets', 'type' => 'clothing'],
                ],
            ],
            [
                'name' => 'Kids',
                'type' => 'main',
                'children' => [
                    ['name' => 'Boys', 'type' => 'clothing'],
                    ['name' => 'Girls', 'type' => 'clothing'],
                ],
            ],
            [
                'name' => 'Accessories',
                'type' => 'main',
                'children' => [
                    ['name' => 'Watches', 'type' => 'item_type'],
                    ['name' => 'Belts', 'type' => 'item_type'],
                    ['name' => 'Hats', 'type' => 'item_type'],
                    ['name' => 'Sunglasses', 'type' => 'item_type'],
                ],
            ],
        ];

        foreach ($mainCategories as $mainCategory) {
            // Check if the category already exists
            $slug = Str::slug($mainCategory['name']);
            $existingCategory = Category::where('slug', $slug)->first();
            
            if (!$existingCategory) {
                $parent = Category::create([
                    'name' => $mainCategory['name'],
                    'slug' => $slug,
                    'type' => $mainCategory['type'],
                    'description' => 'This is the ' . $mainCategory['name'] . ' category',
                    'status' => true,
                ]);

                if (isset($mainCategory['children'])) {
                    foreach ($mainCategory['children'] as $childCategory) {
                        $childSlug = Str::slug($childCategory['name']);
                        $existingChild = Category::where('slug', $childSlug)->first();
                        
                        if (!$existingChild) {
                            $child = Category::create([
                                'name' => $childCategory['name'],
                                'slug' => $childSlug,
                                'type' => $childCategory['type'],
                                'parent_id' => $parent->id,
                                'description' => 'This is the ' . $childCategory['name'] . ' category',
                                'status' => true,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
