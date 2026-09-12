<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('index returns user wishlists with pagination', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product1 */
    $product1 = Product::factory()->create();

    /** @var Product $product2 */
    $product2 = Product::factory()->create();

    Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product1->id,
    ]);

    Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product2->id,
    ]);

    $response = $this->actingAs($user)->getJson('/api/wishlists');

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Wishlists fetched successfully',
        ])
        ->assertJsonCount(2, 'data.data');
});

test('index returns 404 when no wishlists found', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/wishlists');

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'No wishlists found',
        ]);
});

test('index validates pagination parameters', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/wishlists?page=invalid&per_page=200');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['page', 'per_page']);
});

test('index requires authentication', function () {
    /** @var TestCase $this */
    $response = $this->getJson('/api/wishlists');

    $response->assertStatus(401);
});

test('store adds item to wishlist successfully', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/wishlists', [
        'product_id' => $product->id,
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Item added to wishlist successfully',
        ]);

    $this->assertDatabaseHas('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

test('store validates product_id is required', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/wishlists', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['product_id'])
        ->assertJsonFragment([
            'product_id' => ['The product_id field is required.'],
        ]);
});

test('store validates product_id must exist in products table', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/wishlists', [
        'product_id' => 999999,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['product_id'])
        ->assertJsonFragment([
            'product_id' => ['The selected product_id is invalid.'],
        ]);
});

test('store returns 409 when item already in wishlist', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($user)->postJson('/api/wishlists', [
        'product_id' => $product->id,
    ]);

    $response->assertStatus(409)
        ->assertJson([
            'success' => false,
            'message' => 'Item already in wishlist',
        ]);
});

test('store requires authentication', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = Product::factory()->create();

    $response = $this->postJson('/api/wishlists', [
        'product_id' => $product->id,
    ]);

    $response->assertStatus(401);
});

test('destroy removes wishlist item successfully', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Wishlist $wishlist */
    $wishlist = Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($user)->deleteJson("/api/wishlists/{$wishlist->id}");

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Item removed from wishlist successfully',
        ]);

    $this->assertDatabaseMissing('wishlists', [
        'id' => $wishlist->id,
    ]);
});

test('destroy rejects unauthorized user', function () {
    /** @var TestCase $this */
    /** @var User $owner */
    $owner = User::factory()->create();

    /** @var User $otherUser */
    $otherUser = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Wishlist $wishlist */
    $wishlist = Wishlist::create([
        'user_id' => $owner->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($otherUser)->deleteJson("/api/wishlists/{$wishlist->id}");

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'You are not authorized to delete this wishlist item',
        ]);

    $this->assertDatabaseHas('wishlists', [
        'id' => $wishlist->id,
    ]);
});

test('destroy returns 404 for non-existent wishlist item', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->deleteJson('/api/wishlists/999999');

    $response->assertStatus(404);
});

test('destroy requires authentication', function () {
    /** @var TestCase $this */
    /** @var Wishlist $wishlist */
    $wishlist = Wishlist::factory()->create();

    $response = $this->deleteJson("/api/wishlists/{$wishlist->id}");

    $response->assertStatus(401);
});
