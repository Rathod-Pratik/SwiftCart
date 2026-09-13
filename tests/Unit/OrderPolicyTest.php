<?php

use App\Models\Order;
use App\Models\User;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->policy = new OrderPolicy;
});

test('user can view any orders', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->viewAny($user)
    )->toBeTrue();
});

test('user can create an order', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->create($user)
    )->toBeTrue();
});

test('user can view their own order', function () {
    $user = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $user->id,
    ]);

    expect(
        $this->policy->view($user, $order)
    )->toBeTrue();
});

test('user cannot view another users order', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->view($user, $order)
    )->toBeFalse();
});

test('user can delete their own unpaid order', function () {
    $user = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $user->id,
        'payment_status' => 'pending',
    ]);

    expect(
        $this->policy->delete($user, $order)
    )->toBeTrue();
});

test('user cannot delete their own paid order', function () {
    $user = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $user->id,
        'payment_status' => 'paid',
    ]);

    expect(
        $this->policy->delete($user, $order)
    )->toBeFalse();
});

test('user cannot delete another users unpaid order', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $otherUser->id,
        'payment_status' => 'pending',
    ]);

    expect(
        $this->policy->delete($user, $order)
    )->toBeFalse();
});

test('user cannot delete another users paid order', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $otherUser->id,
        'payment_status' => 'paid',
    ]);

    expect(
        $this->policy->delete($user, $order)
    )->toBeFalse();
});

test('user can update their own order', function () {
    $user = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $user->id,
    ]);

    expect(
        $this->policy->update($user, $order)
    )->toBeTrue();
});

test('user cannot update another users order', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->update($user, $order)
    )->toBeFalse();
});
