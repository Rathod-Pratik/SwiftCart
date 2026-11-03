<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('app layout contains preloader and session storage scripts', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('id="global-preloader"', false);
    $response->assertSee('sessionStorage.getItem(\'has_visited\')', false);
    $response->assertSee('.preloader-hidden #global-preloader', false);
});

test('customer product page renders successfully', function () {
    $response = $this->get('/product');

    $response->assertStatus(200);
    $response->assertSee('Products');
});

test('admin category pages render breadcrumbs', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.category.create'))
        ->assertSuccessful()
        ->assertSee('aria-label="Breadcrumb"', false)
        ->assertSee('Catalog management')
        ->assertSee('id="image-preview"', false)
        ->assertSee('URL.createObjectURL(file)', false);

    $this->actingAs($admin)
        ->get(route('admin.category.details'))
        ->assertSuccessful()
        ->assertSee('aria-label="Breadcrumb"', false)
        ->assertSee('Category Details');
});

test('admin product details page renders breadcrumbs', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.product.details'))
        ->assertSuccessful()
        ->assertSee('aria-label="Breadcrumb"', false)
        ->assertSee('Products')
        ->assertSee('Product Details')
        ->assertSee('View Vendor Details');
});

test('admin vendor and user details pages render breadcrumbs and headers', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.vendor.details'))
        ->assertSuccessful()
        ->assertSee('aria-label="Breadcrumb"', false)
        ->assertSee('Vendor management')
        ->assertSee('Review vendor, business, verification, and account information.');

    $this->actingAs($admin)
        ->get(route('admin.user.details'))
        ->assertSuccessful()
        ->assertSee('aria-label="Breadcrumb"', false)
        ->assertSee('User management')
        ->assertSee('Review the registered user information.')
        ->assertSee('id="user-orders-list"', false)
        ->assertSee('Order history')
        ->assertSee('min-h-[calc(100vh-7rem)]', false);
});

test('admin user details API includes activity and order history', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create([
        'order_number' => 'ORD-DETAILS-1',
        'total_amount' => 1250,
    ]);

    $this->actingAs($admin)
        ->getJson("/api/users/{$user->id}")
        ->assertSuccessful()
        ->assertJsonPath('user.id', $user->id)
        ->assertJsonPath('user.orders_count', 1)
        ->assertJsonPath('user.orders.0.id', $order->id)
        ->assertJsonPath('user.orders.0.order_number', 'ORD-DETAILS-1');
});

test('admin navbar renders the authenticated admin image', function () {
    $admin = User::factory()->admin()->create([
        'image' => 'profile-images/admin-avatar.jpg',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee('data-s3-key="profile-images/admin-avatar.jpg"', false)
        ->assertSee('data-s3-auto="true"', false);
});

test('vendor profile exposes the tab switcher to inline handlers', function () {
    $vendor = User::factory()->vendor()->create();

    $this->actingAs($vendor)
        ->get(route('vendor.profile'))
        ->assertSuccessful()
        ->assertSee('window.switchTab = function', false);
});
