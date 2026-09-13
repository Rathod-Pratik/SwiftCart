<?php

use App\Models\Payment;
use App\Models\User;
use App\Policies\PaymentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->policy = new PaymentPolicy;
});

test('user can view any payments', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->viewAny($user)
    )->toBeTrue();
});

test('user can view their own payment', function () {
    $user = User::factory()->create();

    $payment = Payment::factory()->create([
        'user_id' => $user->id,
    ]);

    expect(
        $this->policy->view($user, $payment)
    )->toBeTrue();
});

test('user cannot view another users payment', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $payment = Payment::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->view($user, $payment)
    )->toBeFalse();
});

test('user can create a payment', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->create($user)
    )->toBeTrue();
});

test('user can verify their own payment', function () {
    $user = User::factory()->create();

    $payment = Payment::factory()->create([
        'user_id' => $user->id,
    ]);

    expect(
        $this->policy->verify($user, $payment)
    )->toBeTrue();
});

test('user cannot verify another users payment', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $payment = Payment::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->verify($user, $payment)
    )->toBeFalse();
});
