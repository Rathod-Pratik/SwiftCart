<?php

use App\Models\Review;
use App\Models\User;
use App\Policies\ReviewPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->policy = new ReviewPolicy;
});

test('guest can view any reviews', function () {
    expect(
        $this->policy->viewAny(null)
    )->toBeTrue();
});

test('authenticated user can view any reviews', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->viewAny($user)
    )->toBeTrue();
});

test('guest can view a review', function () {
    $review = Review::factory()->create();

    expect(
        $this->policy->view(null, $review)
    )->toBeTrue();
});

test('authenticated user can view a review', function () {
    $user = User::factory()->create();

    $review = Review::factory()->create();

    expect(
        $this->policy->view($user, $review)
    )->toBeTrue();
});

test('user can create a review', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->create($user)
    )->toBeTrue();
});

test('user can update their own review', function () {
    $user = User::factory()->create();

    $review = Review::factory()->create([
        'user_id' => $user->id,
    ]);

    expect(
        $this->policy->update($user, $review)
    )->toBeTrue();
});

test('user cannot update another users review', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $review = Review::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->update($user, $review)
    )->toBeFalse();
});

test('admin can update another users review', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $otherUser = User::factory()->create();

    $review = Review::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->update($admin, $review)
    )->toBeTrue();
});

test('user can delete their own review', function () {
    $user = User::factory()->create();

    $review = Review::factory()->create([
        'user_id' => $user->id,
    ]);

    expect(
        $this->policy->delete($user, $review)
    )->toBeTrue();
});

test('user cannot delete another users review', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $review = Review::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->delete($user, $review)
    )->toBeFalse();
});

test('admin can delete another users review', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $otherUser = User::factory()->create();

    $review = Review::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect(
        $this->policy->delete($admin, $review)
    )->toBeTrue();
});
