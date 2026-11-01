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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('rating', 3, 2)->nullable()->after('visibility');
            $table->string('offerId')->nullable()->after('rating');
            $table->json('vendor_service_id')->nullable()->after('offerId');
            if (Schema::hasColumn('products', 'related_products')) {
                $table->dropColumn('related_products');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('related_products')->nullable()->after('category_id');
            $table->dropColumn(['rating', 'offerId', 'vendor_service_id']);
        });
    }
};
