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
            if (Schema::hasColumn('products', 'store_link')) {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropColumn('store_link');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'store_link')) {
                Schema::table('products', function (Blueprint $table) {
                    $table->string('store_link')->nullable()->after('category_id');
                });
            }
        });
    }
};
