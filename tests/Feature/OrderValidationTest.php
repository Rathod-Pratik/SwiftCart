<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('index returns user orders with pagination', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Order $order1 */
    $order1 = Order::factory()->create(['user_id' => $user->id]);

    /** @var Order $order2 */
    $order2 = Order::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/orders');

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Orders fetched successfully',
        ]);
});

test('index returns 404 when no orders found', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/orders');

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'No orders found',
        ]);
});

test('index validates pagination parameters', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/orders?page=invalid&per_page=200');

    $response->assertStatus(422)
        ->assertJson(['success' => false]);
});

test('store creates order with items successfully', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product1 */
    $product1 = Product::factory()->create(['price' => 1000]);

    /** @var Product $product2 */
    $product2 = Product::factory()->create(['price' => 500]);

    $response = $this->actingAs($user)->postJson('/api/orders', [
        'payment_method' => 'credit_card',
        'shipping_name' => 'John Doe',
        'shipping_phone' => '1234567890',
        'shipping_address' => '123 Main St',
        'shipping_city' => 'New York',
        'shipping_state' => 'NY',
        'shipping_postal_code' => '10001',
        'shipping_country' => 'USA',
        'discount' => 100,
        'shipping_cost' => 50,
        'tax' => 150,
        'items' => [
            [
                'product_id' => $product1->id,
                'product_name' => $product1->name,
                'quantity' => 2,
                'price' => 1000,
                'total_price' => 2000,
            ],
            [
                'product_id' => $product2->id,
                'product_name' => $product2->name,
                'quantity' => 1,
                'price' => 500,
                'total_price' => 500,
            ],
        ],
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Order created successfully',
        ]);

    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'payment_status' => 'pending',
        'order_status' => 'pending',
    ]);
});

test('store validates required shipping information', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/orders', [
        'payment_method' => 'credit_card',
        'items' => [
            [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => 1,
                'price' => 1000,
                'total_price' => 1000,
            ],
        ],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'shipping_name',
            'shipping_phone',
            'shipping_address',
            'shipping_city',
            'shipping_state',
            'shipping_postal_code',
            'shipping_country',
        ]);
});

test('store validates items array is required and not empty', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/orders', [
        'payment_method' => 'credit_card',
        'shipping_name' => 'John Doe',
        'shipping_phone' => '1234567890',
        'shipping_address' => '123 Main St',
        'shipping_city' => 'New York',
        'shipping_state' => 'NY',
        'shipping_postal_code' => '10001',
        'shipping_country' => 'USA',
        'items' => [],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['items']);
});

test('show returns order with items for authorized user', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Order $order */
    $order = Order::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson("/api/orders/{$order->id}");

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Order fetched successfully',
        ]);
});

test('show rejects unauthorized user', function () {
    /** @var TestCase $this */
    /** @var User $user1 */
    $user1 = User::factory()->create();

    /** @var User $user2 */
    $user2 = User::factory()->create();

    /** @var Order $order */
    $order = Order::factory()->create(['user_id' => $user1->id]);

    $response = $this->actingAs($user2)->getJson("/api/orders/{$order->id}");

    $response->assertStatus(403);
});

test('destroy removes order successfully for authorized user', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Order $order */
    $order = Order::factory()->pending()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->deleteJson("/api/orders/{$order->id}");

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Order deleted successfully',
        ]);

    $this->assertDatabaseMissing('orders', [
        'id' => $order->id,
    ]);
});

test('destroy rejects deletion when payment is paid', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Order $order */
    $order = Order::factory()->paid()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->deleteJson("/api/orders/{$order->id}");

    $response->assertStatus(403);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
    ]);
});

test('destroy rejects unauthorized user', function () {
    /** @var TestCase $this */
    /** @var User $user1 */
    $user1 = User::factory()->create();

    /** @var User $user2 */
    $user2 = User::factory()->create();

    /** @var Order $order */
    $order = Order::factory()->create(['user_id' => $user1->id]);

    $response = $this->actingAs($user2)->deleteJson("/api/orders/{$order->id}");

    $response->assertStatus(403);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
    ]);
});

test('updateStatus changes order status successfully', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Order $order */
    $order = Order::factory()->create(['user_id' => $user->id, 'order_status' => 'pending']);

    $response = $this->actingAs($user)->patchJson("/api/orders/{$order->id}/status", [
        'status' => 'processing',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Order status updated successfully',
        ]);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'order_status' => 'processing',
    ]);
});

test('updateStatus marks order as completed with special message', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Order $order */
    $order = Order::factory()->create(['user_id' => $user->id, 'order_status' => 'processing']);

    $response = $this->actingAs($user)->patchJson("/api/orders/{$order->id}/status", [
        'status' => 'completed',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Order marked as completed successfully',
        ]);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'order_status' => 'completed',
    ]);
});

test('updateStatus validates status is valid enum value', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Order $order */
    $order = Order::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patchJson("/api/orders/{$order->id}/status", [
        'status' => 'invalid_status',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});

test('updateStatus rejects unauthorized user', function () {
    /** @var TestCase $this */
    /** @var User $user1 */
    $user1 = User::factory()->create();

    /** @var User $user2 */
    $user2 = User::factory()->create();

    /** @var Order $order */
    $order = Order::factory()->create(['user_id' => $user1->id]);

    $response = $this->actingAs($user2)->patchJson("/api/orders/{$order->id}/status", [
        'status' => 'completed',
    ]);

    $response->assertStatus(403);
});
