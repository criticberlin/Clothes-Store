<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get customer role ID
        $customerRoleId = DB::table('roles')->where('name', 'customer')->value('id');
        
        // Get customer users
        $customerIds = DB::table('model_has_roles')
            ->where('role_id', $customerRoleId)
            ->where('model_type', 'App\\Models\\User')
            ->pluck('model_id')
            ->toArray();
        
        $customers = User::whereIn('id', $customerIds)->get();
        $products = Product::all();
        
        foreach ($customers as $customer) {
            // Add 0-5 products to each customer's wishlist
            $wishlistProducts = $products->random(rand(0, 5));
            
            foreach ($wishlistProducts as $product) {
                Wishlist::create([
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                ]);
            }
        }
    }
} 