<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
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
        
        // Create orders for each customer
        foreach ($customers as $customer) {
            // Create 1-3 orders per customer
            $orderCount = rand(1, 3);
            
            for ($i = 0; $i < $orderCount; $i++) {
                $status = ['pending', 'processing', 'completed', 'cancelled'][rand(0, 3)];
                
                $order = Order::create([
                    'user_id' => $customer->id,
                    'shipping_address' => $customer->address ?? '123 Customer Street, City, Country',
                    'payment_method' => ['Credit Card', 'PayPal', 'Cash on Delivery'][rand(0, 2)],
                    'status' => $status,
                    'total_amount' => 0, // Will be calculated based on items
                    'created_at' => now()->subDays(rand(1, 30)), // Random date within the last month
                ]);
                
                // Add 1-5 items to the order
                $orderProducts = $products->random(rand(1, 5));
                $totalAmount = 0;
                
                foreach ($orderProducts as $product) {
                    $quantity = rand(1, 3);
                    $price = $product->price;
                    $totalAmount += $price * $quantity;
                    
                    // Get random color and size from the product's available options
                    $color = $product->colors->isNotEmpty() ? $product->colors->random() : null;
                    $size = $product->sizes->isNotEmpty() ? $product->sizes->random() : null;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'color_id' => $color ? $color->id : null,
                        'size_id' => $size ? $size->id : null,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);
                }
                
                // Update the order total
                $order->update(['total_amount' => $totalAmount]);
            }
        }
    }
} 