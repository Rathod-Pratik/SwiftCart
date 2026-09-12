<?php

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('index returns user carts with pagination', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product1 */
    $product1 = Product::factory()->create();

    /** @var Product $product2 */
    $product2 = Product::factory()->create();

    Cart::create([
        'user_id' => $user->id,
        'product_id' => $product1->id,
        'quantity' => 2,
    ]);

    Cart::create([
        'user_id' => $user->id,
        'product_id' => $product2->id,
        'quantity' => 1,
    ]);

    $response = $this->actingAs($user)->getJson('/api/cart');

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Carts fetched successfully',
        ]);
});

test('index returns 404 when no carts found', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/cart');

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'No carts found',
        ]);
});

test('index validates pagination parameters', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/cart?page=invalid&per_page=200');

    $response->assertStatus(422)
        ->assertJson(['success' => false]);
});

test('store adds item to cart successfully', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/cart', [
        'product_id' => $product->id,
        'quantity' => 3,
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Item added to cart successfully',
        ]);

    $this->assertDatabaseHas('carts', [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 3,
    ]);
});

test('store returns 409 when item already in cart', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    Cart::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $response = $this->actingAs($user)->postJson('/api/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response->assertStatus(409)
        ->assertJson([
            'success' => false,
            'message' => 'Item already in cart',
        ]);
});

test('store validates product_id is required', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/cart', [
        'quantity' => 1,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['product_id']);
});

test('store validates quantity is required and positive', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/cart', [
        'product_id' => $product->id,
        'quantity' => 0,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['quantity']);
});

test('update cart item quantity successfully', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Cart $cart */
    $cart = Cart::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response = $this->actingAs($user)->patchJson("/api/cart/{$cart->id}", [
        'quantity' => 5,
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Cart item updated successfully',
        ]);

    $this->assertDatabaseHas('carts', [
        'id' => $cart->id,
        'quantity' => 5,
    ]);
});

test('update rejects unauthorized user', function () {
    /** @var TestCase $this */
    /** @var User $user1 */
    $user1 = User::factory()->create();

    /** @var User $user2 */
    $user2 = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Cart $cart */
    $cart = Cart::create([
        'user_id' => $user1->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response = $this->actingAs($user2)->patchJson("/api/cart/{$cart->id}", [
        'quantity' => 5,
    ]);

    $response->assertStatus(403);
});

test('update validates quantity is positive', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Cart $cart */
    $cart = Cart::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response = $this->actingAs($user)->patchJson("/api/cart/{$cart->id}", [
        'quantity' => -1,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['quantity']);
});

test('destroy removes cart item successfully', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Cart $cart */
    $cart = Cart::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response = $this->actingAs($user)->deleteJson("/api/cart/{$cart->id}");

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Cart item removed successfully',
        ]);

    $this->assertDatabaseMissing('carts', [
        'id' => $cart->id,
    ]);
});

test('destroy rejects unauthorized user', function () {
    /** @var TestCase $this */
    /** @var User $user1 */
    $user1 = User::factory()->create();

    /** @var User $user2 */
    $user2 = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Cart $cart */
    $cart = Cart::create([
        'user_id' => $user1->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response = $this->actingAs($user2)->deleteJson("/api/cart/{$cart->id}");

    $response->assertStatus(403);

    $this->assertDatabaseHas('carts', [
        'id' => $cart->id,
    ]);
});
