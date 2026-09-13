<?php

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('authenticated user can get wishlist list', function () {
    $user = User::factory()->create();

    Wishlist::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    $response = $this->getJson('/api/wishlists');

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data',
        ]);
});

test('user can only get their own wishlist from wishlist list', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $myWishlist = Wishlist::factory()->create([
        'user_id' => $user->id,
    ]);

    $otherWishlist = Wishlist::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $this->actingAs($user);

    $response = $this->getJson('/api/wishlists');

    $response->assertStatus(200);

    $response->assertJsonFragment([
        'id' => $myWishlist->id,
    ]);

    $response->assertJsonMissing([
        'id' => $otherWishlist->id,
    ]);
});

test('user can view their own wishlist', function () {
    $user = User::factory()->create();

    $wishlist = Wishlist::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    $response = $this->getJson("/api/wishlists/{$wishlist->id}");

    $response->assertOk()->assertJsonFragment([
        'id' => $wishlist->id,
    ]);
});

test('user cannot view another users wishlist', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $wishlist = Wishlist::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $this->actingAs($user);

    $response = $this->getJson("/api/wishlists/{$wishlist->id}");

    $response->assertForbidden();
});

test('user can delete their own wishlist', function () {
    $user = User::factory()->create();

    $wishlist = Wishlist::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    $response = $this->deleteJson(
        "/api/wishlists/{$wishlist->id}"
    );

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Item removed from wishlist successfully',
        ]);

    $this->assertDatabaseMissing('wishlists', [
        'id' => $wishlist->id,
    ]);
});

test('user cannot delete another users wishlist', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $wishlist = Wishlist::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $this->actingAs($user);

    $response = $this->deleteJson(
        "/api/wishlists/{$wishlist->id}"
    );

    $response->assertForbidden();

    $this->assertDatabaseHas('wishlists', [
        'id' => $wishlist->id,
        'user_id' => $otherUser->id,
    ]);
});

test('guest cannot access wishlist', function () {
    $response = $this->getJson('/api/wishlists');

    $response->assertUnauthorized();
});

test('guest cannot view a wishlist', function () {
    $user = User::factory()->create();

    $wishlist = Wishlist::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->getJson("/api/wishlists/{$wishlist->id}");

    $response->assertUnauthorized();
});

test('guest cannot delete a wishlist', function () {
    $user = User::factory()->create();

    $wishlist = Wishlist::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->deleteJson(
        "/api/wishlists/{$wishlist->id}"
    );

    $response->assertUnauthorized();
});
