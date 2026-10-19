<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can access query dashboard page and contact route redirects to query', function () {
    /** @var User $admin */
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);

    // Query page works
    $this->actingAs($admin)
        ->get('/admin/query')
        ->assertSuccessful()
        ->assertSee('Queries');

    // Contact route redirects cleanly to /admin/query
    $this->actingAs($admin)
        ->get('/admin/contact')
        ->assertRedirect('/admin/query');
});

test('admin can list queries of user and vendor and view product details', function () {
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    /** @var User $customer */
    $customer = User::factory()->create([
        'name' => 'Alice Customer',
        'email' => 'alice@example.com',
        'role' => 'customer',
    ]);

    /** @var User $vendorUser */
    $vendorUser = User::factory()->create([
        'name' => 'Bob Vendor',
        'email' => 'bob@example.com',
        'role' => 'vendor',
    ]);

    Vendor::factory()->create([
        'user_id' => $vendorUser->id,
        'store_name' => 'Bob Tech Store',
        'status' => 'approved',
    ]);

    $category = Category::factory()->create(['name' => 'Smartphones']);
    $product = Product::factory()->create([
        'vendor_id' => $vendorUser->id,
        'category_id' => $category->id,
        'name' => 'SwiftPhone Ultra',
        'price' => 799.99,
    ]);

    $conversation = Conversation::create([
        'type' => 'customer_vendor',
        'customer_id' => $customer->id,
        'vendor_id' => $vendorUser->id,
        'product_id' => $product->id,
        'last_message_at' => now(),
    ]);

    Message::create([
        'conversation_id' => $conversation->id,
        'sender_id' => $customer->id,
        'body' => 'Does this phone support 5G?',
    ]);

    // Admin lists all queries
    $response = $this->actingAs($admin)->getJson('/api/admin/queries');

    $response->assertSuccessful()
        ->assertJsonPath('data.total', 1)
        ->assertJsonPath('data.data.0.customer.name', 'Alice Customer')
        ->assertJsonPath('data.data.0.vendor.vendor.store_name', 'Bob Tech Store')
        ->assertJsonPath('data.data.0.product.name', 'SwiftPhone Ultra')
        ->assertJsonPath('data.data.0.product.price', '799.99')
        ->assertJsonPath('data.data.0.latest_message.body', 'Does this phone support 5G?');

    // Admin views single query details
    $detailResponse = $this->actingAs($admin)->getJson("/api/admin/queries/{$conversation->id}");

    $detailResponse->assertSuccessful()
        ->assertJsonPath('data.id', $conversation->id)
        ->assertJsonPath('data.customer.email', 'alice@example.com')
        ->assertJsonPath('data.vendor.vendor.store_name', 'Bob Tech Store')
        ->assertJsonPath('data.product.name', 'SwiftPhone Ultra')
        ->assertJsonPath('data.messages.0.body', 'Does this phone support 5G?');
});

test('non-admin users cannot access admin queries api', function () {
    /** @var User $customer */
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)
        ->getJson('/api/admin/queries')
        ->assertStatus(403);
});
