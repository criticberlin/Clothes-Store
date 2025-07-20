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
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('name');
                $table->string('slug')->unique();
                $table->enum('type', ['main', 'clothing', 'item_type'])->default('main');
                $table->text('description')->nullable();
                $table->string('photo')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
                
                $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
            });
        }

        // Create pivot table for category self-relations
        if (!Schema::hasTable('category_category')) {
            Schema::create('category_category', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id');
                $table->unsignedBigInteger('child_id');
                $table->timestamps();
                
                $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
                $table->foreign('child_id')->references('id')->on('categories')->onDelete('cascade');
                
                // Ensure unique parent-child combinations
                $table->unique(['parent_id', 'child_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_category');
        Schema::dropIfExists('categories');
    }
}; 