<?php

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('index returns payments for the authenticated user', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $userOrder = Order::factory()->create(['user_id' => $user->id]);
    $otherOrder = Order::factory()->create(['user_id' => $otherUser->id]);

    Payment::create([
        'order_id' => $userOrder->id,
        'user_id' => $user->id,
        'payment_method' => 'razorpay',
        'payment_status' => 'created',
        'amount' => 1000,
        'currency' => 'INR',
    ]);
    Payment::create([
        'order_id' => $otherOrder->id,
        'user_id' => $otherUser->id,
        'payment_method' => 'razorpay',
        'payment_status' => 'created',
        'amount' => 2000,
        'currency' => 'INR',
    ]);

    $response = $this->actingAs($user)->getJson('/api/payments');

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Payments fetched successfully',
        ])
        ->assertJsonCount(1, 'data.data');
});

test('index requires authentication', function () {
    /** @var TestCase $this */
    $this->getJson('/api/payments')->assertUnauthorized();
});

test('show returns an owned payment', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id]);
    $payment = Payment::create([
        'order_id' => $order->id,
        'user_id' => $user->id,
        'payment_method' => 'razorpay',
        'payment_status' => 'created',
        'amount' => 1000,
        'currency' => 'INR',
    ]);

    $response = $this->actingAs($user)->getJson("/api/payments/{$payment->id}");

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Payment fetched successfully',
        ]);
});

test('show rejects a payment owned by another user', function () {
    /** @var TestCase $this */
    /** @var User $owner */
    $owner = User::factory()->create();
    /** @var User $user */
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $owner->id]);
    $payment = Payment::create([
        'order_id' => $order->id,
        'user_id' => $owner->id,
        'payment_method' => 'razorpay',
        'payment_status' => 'created',
        'amount' => 1000,
        'currency' => 'INR',
    ]);

    $this->actingAs($user)
        ->getJson("/api/payments/{$payment->id}")
        ->assertForbidden();
});

test('create order validates the order id', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/payments/create-order', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['order_id']);
});

test('create order rejects an order owned by another user', function () {
    /** @var TestCase $this */
    /** @var User $owner */
    $owner = User::factory()->create();
    /** @var User $user */
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $owner->id, 'payment_status' => 'pending']);

    $this->actingAs($user)
        ->postJson('/api/payments/create-order', ['order_id' => $order->id])
        ->assertForbidden()
        ->assertJson([
            'success' => false,
            'message' => 'Unauthorized access to this order',
        ]);
});

test('create order rejects an already paid order', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id, 'payment_status' => 'paid']);

    $this->actingAs($user)
        ->postJson('/api/payments/create-order', ['order_id' => $order->id])
        ->assertConflict()
        ->assertJson([
            'success' => false,
            'message' => 'This order has already been paid',
        ]);
});

test('verify validates Razorpay credentials', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/payments/verify', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'razorpay_order_id',
            'razorpay_payment_id',
            'razorpay_signature',
        ]);
});
