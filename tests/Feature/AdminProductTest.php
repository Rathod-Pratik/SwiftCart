<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Notifications\ProductRemovedByAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

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

test('admin can manage promotional flags and remove products', function () {
    $admin = User::factory()->admin()->create();
    $product = Product::factory()->create([
        'is_featured' => false,
        'is_trending' => false,
        'is_limited' => false,
    ]);

    $this->actingAs($admin)
        ->patchJson("/api/admin/products/{$product->id}/flags", [
            'is_featured' => true,
            'is_trending' => true,
            'is_limited' => false,
        ])
        ->assertSuccessful()
        ->assertJsonPath('data.is_featured', true)
        ->assertJsonPath('data.is_trending', true)
        ->assertJsonPath('data.is_limited', false);

    expect($product->fresh()->is_featured)->toBeTrue()
        ->and($product->fresh()->is_trending)->toBeTrue();

    $this->actingAs($admin)
        ->deleteJson("/api/admin/products/{$product->id}", [
            'reason' => 'Policy violation',
            'description' => 'The product listing does not comply with marketplace policy.',
        ])
        ->assertSuccessful();

    expect($product->fresh()->is_removed_by_admin)->toBeTrue()
        ->and($product->fresh()->removed_at)->not->toBeNull();

    $customer = User::factory()->customer()->create();

    $this->actingAs($customer)
        ->getJson('/api/products?search='.urlencode($product->name))
        ->assertJsonPath('data.total', 0);

    $this->actingAs($customer)
        ->getJson("/api/products/{$product->id}")
        ->assertNotFound();
});

test('admin product removal notifies the vendor with the submitted details', function () {
    Notification::fake();

    $admin = User::factory()->admin()->create();
    $vendor = User::factory()->vendor()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'name' => 'Vendor Product',
    ]);

    $this->actingAs($admin)
        ->deleteJson("/api/admin/products/{$product->id}", [
            'reason' => 'Unsafe listing',
            'description' => 'The listing contains prohibited material.',
        ])
        ->assertSuccessful();

    Notification::assertSentTo($vendor, ProductRemovedByAdmin::class, function (ProductRemovedByAdmin $notification) {
        return $notification->productName === 'Vendor Product'
            && $notification->reason === 'Unsafe listing'
            && $notification->description === 'The listing contains prohibited material.';
    });
});

test('vendor cannot change promotional flags', function () {
    $vendor = User::factory()->vendor()->create();
    $vendor->vendor()->create(Vendor::factory()->approved()->make()->toArray());
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'is_featured' => false,
        'is_trending' => false,
        'is_limited' => false,
    ]);

    $this->actingAs($vendor)
        ->patchJson("/api/products/{$product->id}", [
            'is_featured' => true,
            'is_trending' => true,
            'is_limited' => true,
        ])
        ->assertSuccessful();

    expect($product->fresh()->is_featured)->toBeFalse()
        ->and($product->fresh()->is_trending)->toBeFalse()
        ->and($product->fresh()->is_limited)->toBeFalse();
});
