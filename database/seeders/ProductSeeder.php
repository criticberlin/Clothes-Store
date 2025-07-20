<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\User;
use App\Models\ProductImage;
use App\Models\ProductRating;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $adminId = DB::table('model_has_roles')
            ->where('role_id', $adminRoleId)
            ->where('model_type', 'App\\Models\\User')
            ->value('model_id');
        $admin = User::find($adminId);
        
        // Get customer users
        $customerRoleId = DB::table('roles')->where('name', 'customer')->value('id');
        $customerIds = DB::table('model_has_roles')
            ->where('role_id', $customerRoleId)
            ->where('model_type', 'App\\Models\\User')
            ->pluck('model_id')
            ->toArray();
        $customers = User::whereIn('id', $customerIds)->get();
        
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();

        // Sample product data
        $products = [
            [
                'name' => 'Classic T-Shirt',
                'price' => 29.99,
                'description' => 'A comfortable classic t-shirt made from 100% cotton.',
                'quantity' => 100,
                'category_types' => ['clothing', 'item_type'],
                'category_names' => ['T-Shirts', 'Tops'],
            ],
            [
                'name' => 'Slim Fit Jeans',
                'price' => 59.99,
                'description' => 'Stylish slim fit jeans perfect for any casual occasion.',
                'quantity' => 75,
                'category_types' => ['clothing'],
                'category_names' => ['Men Jeans', 'Women Jeans'],
            ],
            [
                'name' => 'Summer Dress',
                'price' => 49.99,
                'description' => 'Light and airy summer dress with floral pattern.',
                'quantity' => 50,
                'category_types' => ['clothing'],
                'category_names' => ['Dresses'],
            ],
            [
                'name' => 'Leather Jacket',
                'price' => 199.99,
                'description' => 'Classic leather jacket with a modern twist.',
                'quantity' => 30,
                'category_types' => ['clothing'],
                'category_names' => ['Men Jackets', 'Women Jackets'],
            ],
            [
                'name' => 'Kids Hoodie',
                'price' => 34.99,
                'description' => 'Warm and comfortable hoodie for kids.',
                'quantity' => 60,
                'category_types' => ['clothing'],
                'category_names' => ['Boys', 'Girls'],
            ],
            [
                'name' => 'Analog Watch',
                'price' => 129.99,
                'description' => 'Classic analog watch with leather strap.',
                'quantity' => 25,
                'category_types' => ['item_type'],
                'category_names' => ['Watches'],
            ],
            [
                'name' => 'Leather Belt',
                'price' => 39.99,
                'description' => 'Premium leather belt with metal buckle.',
                'quantity' => 40,
                'category_types' => ['item_type'],
                'category_names' => ['Belts'],
            ],
            [
                'name' => 'Formal Shirt',
                'price' => 69.99,
                'description' => 'Crisp formal shirt for professional settings.',
                'quantity' => 55,
                'category_types' => ['clothing'],
                'category_names' => ['Shirts'],
            ],
            [
                'name' => 'Pleated Skirt',
                'price' => 45.99,
                'description' => 'Elegant pleated skirt for a sophisticated look.',
                'quantity' => 35,
                'category_types' => ['clothing'],
                'category_names' => ['Skirts'],
            ],
            [
                'name' => 'Casual Pants',
                'price' => 54.99,
                'description' => 'Comfortable casual pants for everyday wear.',
                'quantity' => 65,
                'category_types' => ['clothing'],
                'category_names' => ['Pants'],
            ],
        ];

        foreach ($products as $productData) {
            // Create the product
            $product = Product::create([
                'name' => $productData['name'],
                'code' => 'PRD-' . Str::random(8),
                'price' => $productData['price'],
                'description' => $productData['description'],
                'quantity' => $productData['quantity'],
                'created_by' => $admin ? $admin->id : null,
            ]);

            // Attach categories
            $categoryIds = [];
            foreach ($productData['category_names'] as $categoryName) {
                $category = Category::where('name', $categoryName)->first();
                if ($category) {
                    $categoryIds[] = $category->id;
                }
            }
            if (!empty($categoryIds)) {
                $product->categories()->attach($categoryIds);
            }

            // Attach random colors (2-4)
            if ($colors->isNotEmpty()) {
                $randomColors = $colors->random(min(rand(2, 4), $colors->count()));
                $product->colors()->attach($randomColors->pluck('id')->toArray());
            }

            // Attach random sizes (3-5)
            if ($sizes->isNotEmpty()) {
                $randomSizes = $sizes->random(min(rand(3, 5), $sizes->count()));
                $product->sizes()->attach($randomSizes->pluck('id')->toArray());
            }

            // Add product images (1-3)
            for ($i = 1; $i <= rand(1, 3); $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'filename' => 'product_' . $product->id . '_' . $i . '.jpg',
                    'sort_order' => $i,
                ]);
            }

            // Add product ratings (0-5)
            if ($customers->isNotEmpty()) {
                $numRatings = rand(0, 5);
                for ($i = 0; $i < $numRatings; $i++) {
                    $customer = $customers->random();
                    ProductRating::create([
                        'product_id' => $product->id,
                        'user_id' => $customer->id,
                        'rating' => rand(3, 5),
                        'review' => 'This is a review for ' . $product->name,
                        'is_approved' => true,
                    ]);
                }
            }
        }

        // Create product recommendations if table exists
        if (Schema::hasTable('product_recommendations')) {
            $allProducts = Product::all();
            if ($allProducts->count() > 1) {
                foreach ($allProducts as $product) {
                    $otherProducts = $allProducts->where('id', '!=', $product->id);
                    if ($otherProducts->isNotEmpty()) {
                        $recommendCount = min(rand(2, 4), $otherProducts->count());
                        $randomProducts = $otherProducts->random($recommendCount);
                        foreach ($randomProducts as $index => $otherProduct) {
                            $product->recommendations()->attach($otherProduct->id, ['sort_order' => $index + 1]);
                        }
                    }
                }
            }
        }
    }
} 