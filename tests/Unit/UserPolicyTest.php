<?php

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->policy = new UserPolicy;
});

test('admin can perform any user ability', function () {
    $admin = User::factory()->create();
    $admin->role = 'admin';

    expect(
        $this->policy->before($admin, 'view')
    )->toBeTrue();
});

test('non admin user does not bypass policy authorization', function () {
    $user = User::factory()->create();

    $user->role = 'user';

    expect(
        $this->policy->before($user, 'view')
    )->toBeNull();
});

test('user cannot view any users', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->viewAny($user)
    )->toBeFalse();
});

test('user can view their own profile', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->view($user, $user)
    )->toBeTrue();
});

test('user cannot view another users profile', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    expect(
        $this->policy->view($user, $otherUser)
    )->toBeFalse();
});

test('user can create a user', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->create($user)
    )->toBeTrue();
});

test('user can update their own profile', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->update($user, $user)
    )->toBeTrue();
});

test('user cannot update another users profile', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    expect(
        $this->policy->update($user, $otherUser)
    )->toBeFalse();
});

test('user can delete their own account', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->delete($user, $user)
    )->toBeTrue();
});

test('user cannot delete another users account', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    expect(
        $this->policy->delete($user, $otherUser)
    )->toBeFalse();
});

test('user cannot restore a user', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->restore($user, $user)
    )->toBeFalse();
});

test('user can force delete their own account', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->forceDelete($user, $user)
    )->toBeTrue();
});

test('user cannot force delete another users account', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    expect(
        $this->policy->forceDelete($user, $otherUser)
    )->toBeFalse();
});
