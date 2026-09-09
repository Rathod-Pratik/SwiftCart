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
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            // Customer
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Unique order number
            $table->string('order_number')->unique();

            // Price information
            $table->decimal('subtotal', 12, 2);

            $table->decimal('discount', 12, 2)
                ->default(0);

            $table->decimal('shipping_cost', 12, 2)
                ->default(0);

            $table->decimal('tax', 12, 2)
                ->default(0);

            $table->decimal('total_amount', 12, 2);

            // Order status
            $table->enum('order_status', [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
                'returned',
            ])->default('pending');

            // Payment
            $table->string('payment_method');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded',
            ])->default('pending');

            $table->string('transaction_id')->nullable();

            // Shipping details
            $table->string('shipping_name');

            $table->string('shipping_phone');

            $table->text('shipping_address');

            $table->string('shipping_city');

            $table->string('shipping_state');

            $table->string('shipping_postal_code');

            $table->string('shipping_country')
                ->default('India');

            // Customer notes
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
