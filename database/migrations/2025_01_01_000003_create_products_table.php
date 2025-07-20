<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create colors table
        if (!Schema::hasTable('colors')) {
            Schema::create('colors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('hex_code')->nullable();
                $table->timestamps();
            });
        }

        // Create sizes table
        if (!Schema::hasTable('sizes')) {
            Schema::create('sizes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        // Create products table
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->decimal('price', 10, 2);
                $table->text('description')->nullable();
                $table->string('photo')->nullable();
                $table->string('image')->nullable();
                $table->string('image_path')->nullable();
                $table->integer('quantity')->default(0);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Create pivot table for products and categories
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

        // Create pivot table for products and colors
        if (!Schema::hasTable('color_product')) {
            Schema::create('color_product', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('color_id');
                $table->unsignedBigInteger('product_id');
                $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Create pivot table for products and sizes
        if (!Schema::hasTable('product_size')) {
            Schema::create('product_size', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('size_id');
                $table->unsignedBigInteger('product_id');
                $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Create product images table
        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->string('filename');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // Create product recommendations table
        if (!Schema::hasTable('product_recommendations')) {
            Schema::create('product_recommendations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('recommended_product_id')->constrained('products')->onDelete('cascade');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                
                // Ensure we don't have duplicate recommendations
                $table->unique(['product_id', 'recommended_product_id']);
            });
        }

        // Create product ratings table
        if (!Schema::hasTable('product_ratings')) {
            Schema::create('product_ratings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('rating')->comment('1-5 stars');
                $table->text('review')->nullable();
                $table->boolean('is_approved')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ratings');
        Schema::dropIfExists('product_recommendations');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_size');
        Schema::dropIfExists('color_product');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('products');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('colors');
    }
}; 