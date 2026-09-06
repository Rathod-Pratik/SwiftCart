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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->json('image')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->json('related_products')->nullable();
            $table->string('store_link')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_limited')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->string('features');
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->json('about')->nullable();
            $table->json('menifectures_images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
