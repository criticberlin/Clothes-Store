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
        // Create pivot table for products and colors
        Schema::create('color_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });

        // Create pivot table for products and sizes
        Schema::create('product_size', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('size_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_size');
        Schema::dropIfExists('color_product');
    }
};
