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
        Schema::table('orders', function (Blueprint $table) {
            // Add address relationship
            $table->foreignId('address_id')->nullable()->constrained();
            
            // Add payment method relationship
            $table->foreignId('payment_method_id')->nullable()->constrained();
            
            // Add shipping method relationship
            $table->foreignId('shipping_method_id')->nullable()->constrained();
            
            // Add promo code relationship
            $table->foreignId('promo_code_id')->nullable()->constrained();
            
            // Add payment and shipping details
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('payment_fee', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            
            // Add payment status
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            
            // Add payment details
            $table->string('transaction_id')->nullable();
            $table->json('payment_details')->nullable();
            
            // Add terms acceptance flag
            $table->boolean('terms_accepted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropForeign(['payment_method_id']);
            $table->dropForeign(['shipping_method_id']);
            $table->dropForeign(['promo_code_id']);
            
            $table->dropColumn([
                'address_id',
                'payment_method_id',
                'shipping_method_id',
                'promo_code_id',
                'subtotal',
                'shipping_cost',
                'payment_fee',
                'discount_amount',
                'total',
                'payment_status',
                'transaction_id',
                'payment_details',
                'terms_accepted'
            ]);
        });
    }
};
