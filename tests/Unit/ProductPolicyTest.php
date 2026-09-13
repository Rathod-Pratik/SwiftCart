<?php

use App\Models\Product;
use App\Models\User;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->policy = new ProductPolicy;
});

test('guest can view any products', function () {
    expect(
        $this->policy->viewAny(null)
    )->toBeTrue();
});

test('authenticated user can view any products', function () {
    $user = User::factory()->create();

    expect(
        $this->policy->viewAny($user)
    )->toBeTrue();
});

test('guest can view active public product', function () {
    $product = Product::factory()->create([
        'status' => 'active',
        'visibility' => 'public',
    ]);

    expect(
        $this->policy->view(null, $product)
    )->toBeTrue();
});

test('guest cannot view inactive public product', function () {
    $product = Product::factory()->create([
        'status' => 'inactive',
        'visibility' => 'public',
    ]);

    expect(
        $this->policy->view(null, $product)
    )->toBeFalse();
});

test('guest cannot view active private product', function () {
    $product = Product::factory()->create([
        'status' => 'active',
        'visibility' => 'private',
    ]);

    expect(
        $this->policy->view(null, $product)
    )->toBeFalse();
});

test('admin can view private product', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $product = Product::factory()->create([
        'status' => 'inactive',
        'visibility' => 'private',
    ]);

    expect(
        $this->policy->view($admin, $product)
    )->toBeTrue();
});

test('vendor can view their own private product', function () {
    $vendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'status' => 'inactive',
        'visibility' => 'private',
    ]);

    expect(
        $this->policy->view($vendor, $product)
    )->toBeTrue();
});

test('vendor cannot view another vendors private product', function () {
    $vendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    $otherVendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    $product = Product::factory()->create([
        'vendor_id' => $otherVendor->id,
        'status' => 'inactive',
        'visibility' => 'private',
    ]);

    expect(
        $this->policy->view($vendor, $product)
    )->toBeFalse();
});

test('admin can create a product', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    expect(
        $this->policy->create($admin)
    )->toBeTrue();
});

test('vendor can create a product', function () {
    $vendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    expect(
        $this->policy->create($vendor)
    )->toBeTrue();
});

test('normal user cannot create a product', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    expect(
        $this->policy->create($user)
    )->toBeFalse();
});

test('admin can update any product', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $product = Product::factory()->create();

    expect(
        $this->policy->update($admin, $product)
    )->toBeTrue();
});

test('vendor can update their own product', function () {
    $vendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
    ]);

    expect(
        $this->policy->update($vendor, $product)
    )->toBeTrue();
});

test('vendor cannot update another vendors product', function () {
    $vendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    $otherVendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    $product = Product::factory()->create([
        'vendor_id' => $otherVendor->id,
    ]);

    expect(
        $this->policy->update($vendor, $product)
    )->toBeFalse();
});

test('normal user cannot update a product', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $product = Product::factory()->create();

    expect(
        $this->policy->update($user, $product)
    )->toBeFalse();
});

test('admin can delete any product', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $product = Product::factory()->create();

    expect(
        $this->policy->delete($admin, $product)
    )->toBeTrue();
});

test('vendor can delete their own product', function () {
    $vendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
    ]);

    expect(
        $this->policy->delete($vendor, $product)
    )->toBeTrue();
});

test('vendor cannot delete another vendors product', function () {
    $vendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    $otherVendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    $product = Product::factory()->create([
        'vendor_id' => $otherVendor->id,
    ]);

    expect(
        $this->policy->delete($vendor, $product)
    )->toBeFalse();
});

test('normal user cannot delete a product', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $product = Product::factory()->create();

    expect(
        $this->policy->delete($user, $product)
    )->toBeFalse();
});

test('admin can bypass product policy', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    expect(
        $this->policy->before($admin)
    )->toBeTrue();
});

test('non admin user does not bypass product policy', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    expect(
        $this->policy->before($user)
    )->toBeNull();
});
