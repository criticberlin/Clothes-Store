<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the 'category' column exists in products table before trying to drop it
        if (Schema::hasColumn('products', 'category')) {
            // First, copy category names to a temporary table to preserve the data
            $products = DB::table('products')->select('id', 'category')->get();
            $categoryMap = [];
            
            // Create categories if they don't exist and build a mapping
            foreach ($products as $product) {
                if (!empty($product->category)) {
                    $categoryName = $product->category;
                    
                    // Check if this category exists
                    $category = DB::table('categories')
                        ->where('name', $categoryName)
                        ->first();
                    
                    if (!$category) {
                        // Create the category
                        $slug = \Illuminate\Support\Str::slug($categoryName);
                        $categoryId = DB::table('categories')->insertGetId([
                            'name' => $categoryName,
                            'slug' => $slug,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        $categoryId = $category->id;
                    }
                    
                    $categoryMap[$product->id] = $categoryId;
                }
            }
            
            // Remove the string 'category' column from products table
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('category');
            });
            
            // Create pivot table if it doesn't exist
            if (!Schema::hasTable('category_product')) {
                Schema::create('category_product', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('category_id');
                    $table->unsignedBigInteger('product_id');
                    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                    $table->timestamps();
                });
            }
            
            // Add the relationships to the pivot table
            foreach ($categoryMap as $productId => $categoryId) {
                DB::table('category_product')->insert([
                    'product_id' => $productId,
                    'category_id' => $categoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // Just ensure the pivot table exists
            if (!Schema::hasTable('category_product')) {
                Schema::create('category_product', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('category_id');
                    $table->unsignedBigInteger('product_id');
                    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                    $table->timestamps();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't add back the category column in down migration to avoid data loss
        // But we'll keep the pivot table
    }
}; 