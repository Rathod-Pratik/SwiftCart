<?php

use App\Models\Category;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->policy = new CategoryPolicy;
});

test('guest can view any categories', function () {
    expect(
        $this->policy->viewAny(null)
    )->toBeTrue();
});

test('authenticated user can view any categories', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->viewAny($user)
    )->toBeTrue();
});

test('guest can view a category', function () {
    $category = Category::factory()->create();

    expect(
        $this->policy->view(null, $category)
    )->toBeTrue();
});

test('authenticated user can view a category', function () {
    $user = User::factory()->create();

    $category = Category::factory()->create();

    expect(
        $this->policy->view($user, $category)
    )->toBeTrue();
});

test('admin can create a category', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    expect(
        $this->policy->create($admin)
    )->toBeTrue();
});

test('non admin user cannot create a category', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    expect(
        $this->policy->create($user)
    )->toBeFalse();
});

test('admin can update a category', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $category = Category::factory()->create();

    expect(
        $this->policy->update($admin, $category)
    )->toBeTrue();
});

test('non admin user cannot update a category', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $category = Category::factory()->create();

    expect(
        $this->policy->update($user, $category)
    )->toBeFalse();
});

test('admin can delete a category', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $category = Category::factory()->create();

    expect(
        $this->policy->delete($admin, $category)
    )->toBeTrue();
});

test('non admin user cannot delete a category', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $category = Category::factory()->create();

    expect(
        $this->policy->delete($user, $category)
    )->toBeFalse();
});

test('admin can bypass category policy', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    expect(
        $this->policy->before($admin)
    )->toBeTrue();
});

test('non admin user does not bypass category policy', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    expect(
        $this->policy->before($user)
    )->toBeNull();
});
