<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('vendor can list only their products with rating summaries', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);
    $otherVendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
        'name' => 'Vendor Headphones',
    ]);
    $otherProduct = Product::factory()->create([
        'vendor_id' => $otherVendor->id,
        'category_id' => $category->id,
    ]);
    Review::factory()->create([
        'product_id' => $product->id,
        'rating' => 5,
    ]);
    Review::factory()->create([
        'product_id' => $product->id,
        'rating' => 3,
    ]);

    $this->actingAs($vendor)
        ->getJson('/api/vendor/ratings/products?search=Headphones')
        ->assertSuccessful()
        ->assertJsonPath('data.total', 1)
        ->assertJsonPath('data.data.0.id', $product->id)
        ->assertJsonPath('data.data.0.reviews_count', 2)
        ->assertJsonPath('data.data.0.reviews_avg_rating', 4);

    expect($otherProduct->vendor_id)->toBe($otherVendor->id);
});

test('vendor can view ratings for their product and cannot view another vendors product', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);
    $otherVendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
    ]);
    $otherProduct = Product::factory()->create([
        'vendor_id' => $otherVendor->id,
        'category_id' => $category->id,
    ]);
    $review = Review::factory()->create([
        'product_id' => $product->id,
        'rating' => 4,
        'comment' => 'Good product',
    ]);

    $this->actingAs($vendor)
        ->getJson("/api/vendor/ratings/products/{$product->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.product.id', $product->id)
        ->assertJsonPath('data.product.reviews_count', 1)
        ->assertJsonPath('data.reviews.data.0.id', $review->id)
        ->assertJsonPath('data.reviews.data.0.comment', 'Good product');

    $this->actingAs($vendor)
        ->getJson("/api/vendor/ratings/products/{$otherProduct->id}")
        ->assertNotFound();
});
