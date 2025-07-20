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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('governorate_id')->constrained();
            $table->foreignId('city_id')->constrained();
            $table->string('full_name');
            $table->string('mobile_number');
            $table->string('street_address');
            $table->string('building_number')->nullable();
            $table->string('floor_number')->nullable();
            $table->string('apartment_number')->nullable();
            $table->text('delivery_instructions')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
