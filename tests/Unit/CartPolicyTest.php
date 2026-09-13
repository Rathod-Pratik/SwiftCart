<?php

use App\Models\Cart;
use App\Models\User;
use App\Policies\CartPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);
beforeEach(function () {
    $this->policy = new CartPolicy;
});

test('user can view any carts', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->viewAny($user)
    )->toBeTrue();
});

test('user can create a cart', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->create($user)
    )->toBeTrue();
});

test('user can view their own cart', function () {
    $user = User::factory()->create();

    $cart = Cart::factory()->create([
        'user_id' => $user->id,
    ]);

    expect(
        $this->policy->view($user, $cart)
    )->toBeTrue();
});

test('user cannot view another users cart', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $cart = Cart::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->view($user, $cart)
    )->toBeFalse();
});

test('user can update their own cart', function () {
    $user = User::factory()->create();

    $cart = Cart::factory()->create([
        'user_id' => $user->id,
    ]);

    expect(
        $this->policy->update($user, $cart)
    )->toBeTrue();
});

test('user cannot update another users cart', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $cart = Cart::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->update($user, $cart)
    )->toBeFalse();
});

test('user can delete their own cart', function () {
    $user = User::factory()->create();

    $cart = Cart::factory()->create([
        'user_id' => $user->id,
    ]);

    expect(
        $this->policy->delete($user, $cart)
    )->toBeTrue();
});

test('user cannot delete another users cart', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $cart = Cart::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->delete($user, $cart)
    )->toBeFalse();
});
