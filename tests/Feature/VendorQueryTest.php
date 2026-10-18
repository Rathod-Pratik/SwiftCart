<?php

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('vendor can list only assigned customer queries and search them', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);
    $otherVendor = User::factory()->create(['role' => 'vendor']);
    $customer = User::factory()->create(['role' => 'user']);
    $otherCustomer = User::factory()->create(['role' => 'user']);
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
        'name' => 'Vendor Camera',
    ]);
    $otherProduct = Product::factory()->create([
        'vendor_id' => $otherVendor->id,
        'category_id' => $category->id,
    ]);

    $query = Conversation::create([
        'type' => 'customer_vendor',
        'customer_id' => $customer->id,
        'vendor_id' => $vendor->id,
        'product_id' => $product->id,
        'last_message_at' => now(),
    ]);
    Message::create([
        'conversation_id' => $query->id,
        'sender_id' => $customer->id,
        'body' => 'Camera question',
    ]);
    Conversation::create([
        'type' => 'customer_vendor',
        'customer_id' => $otherCustomer->id,
        'vendor_id' => $otherVendor->id,
        'product_id' => $otherProduct->id,
    ]);

    $response = $this->actingAs($vendor)->getJson('/api/vendor/queries?search=Camera');

    $response->assertSuccessful()
        ->assertJsonPath('data.total', 1)
        ->assertJsonPath('data.data.0.id', $query->id)
        ->assertJsonPath('data.data.0.product.name', 'Vendor Camera')
        ->assertJsonPath('data.data.0.latest_message.body', 'Camera question');
});

test('vendor can view and reply to an assigned query but not another vendors query', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);
    $otherVendor = User::factory()->create(['role' => 'vendor']);
    $customer = User::factory()->create(['role' => 'user']);
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
    ]);
    $otherProduct = Product::factory()->create([
        'vendor_id' => $otherVendor->id,
        'category_id' => $category->id,
    ]);
    $query = Conversation::create([
        'type' => 'customer_vendor',
        'customer_id' => $customer->id,
        'vendor_id' => $vendor->id,
        'product_id' => $product->id,
    ]);
    $otherQuery = Conversation::create([
        'type' => 'customer_vendor',
        'customer_id' => $customer->id,
        'vendor_id' => $otherVendor->id,
        'product_id' => $otherProduct->id,
    ]);

    $this->actingAs($vendor)
        ->getJson("/api/vendor/queries/{$query->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.id', $query->id)
        ->assertJsonPath('data.customer.id', $customer->id);

    $this->actingAs($vendor)
        ->postJson("/api/vendor/queries/{$query->id}/messages", ['body' => 'Thanks for your question.'])
        ->assertCreated()
        ->assertJsonPath('data.body', 'Thanks for your question.');

    $this->actingAs($vendor)
        ->getJson("/api/vendor/queries/{$otherQuery->id}")
        ->assertNotFound();
});
