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
        Schema::table('coupons', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->decimal('minimum_order_amount', 10, 2)->nullable();
            $table->decimal('maximum_discount', 10, 2)->nullable();
            $table->unsignedInteger('per_user_limit')->default(1);
            $table->dateTime('starts_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'minimum_order_amount',
                'maximum_discount',
                'per_user_limit',
                'starts_at',
            ]);
        });
    }
};
