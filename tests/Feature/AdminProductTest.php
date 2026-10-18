<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can list products with search and catalog relationships', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $vendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
        'name' => 'Admin Search Laptop',
    ]);

    $this->actingAs($admin)
        ->getJson('/api/products?search=Search%20Laptop')
        ->assertSuccessful()
        ->assertJsonPath('data.total', 1)
        ->assertJsonPath('data.data.0.id', $product->id)
        ->assertJsonPath('data.data.0.category.id', $category->id)
        ->assertJsonPath('data.data.0.vendor.id', $vendor->id);
});

test('admin can view product details', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $vendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
    ]);

    $this->actingAs($admin)
        ->getJson("/api/products/{$product->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.id', $product->id)
        ->assertJsonPath('data.category.id', $category->id)
        ->assertJsonPath('data.vendor.id', $vendor->id);
});
