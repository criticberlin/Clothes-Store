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
        Schema::table('categories', function (Blueprint $table) {
            // Check if parent_id column exists before adding it
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('id');
                $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
            }
            
            // Check if type column exists before adding it
            if (!Schema::hasColumn('categories', 'type')) {
                $table->enum('type', ['main', 'clothing', 'item_type'])->default('main')->after('slug');
            }
            
            // Check if status column exists before adding it
            if (!Schema::hasColumn('categories', 'status')) {
                $table->boolean('status')->default(true)->after('photo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Only drop foreign key if it exists
            if (Schema::hasColumn('categories', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
            
            // Only drop status column if it exists
            if (Schema::hasColumn('categories', 'status')) {
                $table->dropColumn('status');
            }
            
            // Only drop type column if it exists
            if (Schema::hasColumn('categories', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
}; 