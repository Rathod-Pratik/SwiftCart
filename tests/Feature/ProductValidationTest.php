<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('store returns validation errors when required fields are missing', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);

    $response = $this->actingAs($vendor)->postJson('/api/products', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'price',
            'stock',
            'category_id',
            'features',
        ]);
});

test('store rejects a negative price, discount, and stock', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();

    $response = $this->actingAs($vendor)->postJson('/api/products', [
        'name' => 'Test Product',
        'price' => -10,
        'discount' => -5,
        'stock' => -1,
        'category_id' => $category->id,
        'features' => 'some features',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['price', 'discount', 'stock']);
});

test('store rejects a category_id that does not exist', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);

    $response = $this->actingAs($vendor)->postJson('/api/products', [
        'name' => 'Test Product',
        'price' => 100,
        'stock' => 10,
        'category_id' => 999999, // non-existent
        'features' => 'some features',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['category_id']);
});

test('store rejects a vendor_id that does not exist', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();

    $response = $this->actingAs($vendor)->postJson('/api/products', [
        'name' => 'Test Product',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id,
        'features' => 'some features',
        'vendor_id' => 999999, // non-existent
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['vendor_id']);
});

test('store rejects an invalid status or visibility value', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();

    $response = $this->actingAs($vendor)->postJson('/api/products', [
        'name' => 'Test Product',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id,
        'features' => 'some features',
        'status' => 'archived', // not in allowed list
        'visibility' => 'hidden', // not in allowed list
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['status', 'visibility']);
});

test('store rejects image field values that are not strings', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();

    $response = $this->actingAs($vendor)->postJson('/api/products', [
        'name' => 'Test Product',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id,
        'features' => 'some features',
        'image' => [123, 456], // must be strings, not integers
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['image.0', 'image.1']);
});

test('store requires a title when information_sections are provided', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();

    $response = $this->actingAs($vendor)->postJson('/api/products', [
        'name' => 'Test Product',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id,
        'features' => 'some features',
        'information_sections' => [
            ['features' => ['durable']], // missing required 'title'
        ],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['information_sections.0.title']);
});

test('store succeeds with valid data and defaults vendor_id to the authenticated vendor', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();

    $response = $this->actingAs($vendor)->postJson('/api/products', [
        'name' => 'Wireless Mouse',
        'description' => 'A smooth wireless mouse',
        'price' => 499.99,
        'stock' => 50,
        'category_id' => $category->id,
        'features' => 'ergonomic, USB-C, silent click',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Product created successfully',
        ]);

    $this->assertDatabaseHas('products', [
        'name' => 'Wireless Mouse',
        'vendor_id' => $vendor->id,
    ]);
});

test('store creates related information_sections when valid', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $category = Category::factory()->create();

    $response = $this->actingAs($vendor)->postJson('/api/products', [
        'name' => 'Smart Watch',
        'price' => 2999,
        'stock' => 20,
        'category_id' => $category->id,
        'features' => 'heart rate monitor, GPS',
        'information_sections' => [
            ['title' => 'Battery Life', 'features' => ['48 hours standby']],
            ['title' => 'Water Resistance', 'features' => ['5 ATM rated']],
        ],
    ]);

    $response->assertStatus(201);

    $product = Product::where('name', 'Smart Watch')->first();
    expect($product->informationSections()->count())->toBe(2);
});

test('a non-vendor, non-admin user cannot create a product', function () {
    /** @var TestCase $this */
    /** @var User $customer */
    $customer = User::factory()->create(['role' => 'user']);
    $category = Category::factory()->create();

    $response = $this->actingAs($customer)->postJson('/api/products', [
        'name' => 'Test Product',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id,
        'features' => 'some features',
    ]);

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'You are not authorized to create products',
        ]);
});

test('update allows partial payloads without requiring all fields', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $response = $this->actingAs($vendor)->patchJson("/api/products/{$product->id}", [
        'name' => 'Updated Name Only',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Product updated successfully',
        ]);

    expect($product->fresh()->name)->toBe('Updated Name Only');
});

test('update still validates provided fields even though they are optional', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $response = $this->actingAs($vendor)->patchJson("/api/products/{$product->id}", [
        'price' => -50, // invalid even though 'sometimes' is used
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['price']);
});

test('update rejects an invalid category_id if provided', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $response = $this->actingAs($vendor)->patchJson("/api/products/{$product->id}", [
        'category_id' => 999999,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['category_id']);
});

test('a vendor cannot change vendor_id when updating their own product', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    /** @var User $otherVendor */
    $otherVendor = User::factory()->create(['role' => 'vendor']);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $response = $this->actingAs($vendor)->patchJson("/api/products/{$product->id}", [
        'vendor_id' => $otherVendor->id,
        'name' => 'Attempted Ownership Change',
    ]);

    $response->assertStatus(200);

    // vendor_id should remain unchanged despite being in the payload
    expect($product->fresh()->vendor_id)->toBe($vendor->id);
});

test('a vendor cannot update another vendor\'s product', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    /** @var User $otherVendor */
    $otherVendor = User::factory()->create(['role' => 'vendor']);
    $product = Product::factory()->create(['vendor_id' => $otherVendor->id]);

    $response = $this->actingAs($vendor)->patchJson("/api/products/{$product->id}", [
        'name' => 'Trying to edit someone else\'s product',
    ]);

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'You are not authorized to update this product',
        ]);
});

test('update replaces information_sections when a new array is provided', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $product->informationSections()->create(['title' => 'Old Section']);

    $response = $this->actingAs($vendor)->patchJson("/api/products/{$product->id}", [
        'information_sections' => [
            ['title' => 'New Section', 'features' => ['updated feature']],
        ],
    ]);

    $response->assertStatus(200);

    expect($product->informationSections()->count())->toBe(1);
    expect($product->informationSections()->first()->title)->toBe('New Section');
});
