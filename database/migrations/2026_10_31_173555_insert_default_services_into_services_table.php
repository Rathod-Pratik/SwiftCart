<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $defaults = [
            [
                'vendor_id' => null,
                'name' => 'Cash on Delivery',
                'icon' => 'banknote',
                'description' => 'Pay securely with cash upon delivery at your doorstep.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => null,
                'name' => 'Express Shipping',
                'icon' => 'truck',
                'description' => 'Fast delivery within 24 to 48 hours directly to your address.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => null,
                'name' => 'Free Delivery',
                'icon' => 'box',
                'description' => 'Standard free shipping included on this product.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => null,
                'name' => '7 Days Replacement',
                'icon' => 'refresh-cw',
                'description' => 'Hassle-free replacement policy if damaged or defective.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => null,
                'name' => '2-Year Warranty',
                'icon' => 'shield-check',
                'description' => 'Official manufacturer and seller warranty coverage.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => null,
                'name' => '24/7 Customer Support',
                'icon' => 'headset',
                'description' => 'Dedicated priority support anytime you need help.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => null,
                'name' => 'Verified Authenticity',
                'icon' => 'award',
                'description' => '100% genuine product sourced directly from verified sellers.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('services')->insert($defaults);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('services')->whereNull('vendor_id')->delete();
    }
};
