<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to login when accessing customer profile page', function () {
    $response = $this->get('/profile');

    $response->assertRedirect('/login');
});

test('authenticated customer can view the profile page', function () {
    /** @var User $customer */
    $customer = User::factory()->create([
        'name' => 'Pratik Customer',
        'email' => 'pratik@example.com',
        'phone' => '1234567890',
        'address' => '123 SwiftCart St, Tech City',
        'role' => 'customer',
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($customer)->get('/profile');

    $response->assertOk()
        ->assertViewIs('customer.profile.index')
        ->assertSee('Pratik Customer')
        ->assertSee('pratik@example.com')
        ->assertSee('Personal Information')
        ->assertSee('My Orders')
        ->assertSee('Delivery Address')
        ->assertSee('Password & Security')
        ->assertSee('Email Verified');
});

test('customer profile page displays customer order history', function () {
    /** @var User $customer */
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'order_number' => 'ORD-TEST-999',
        'order_status' => 'completed',
        'total_amount' => 149.99,
        'shipping_name' => $customer->name,
    ]);

    $product = Product::factory()->create();

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => 'Premium Wireless Headphones',
        'quantity' => 1,
        'price' => 149.99,
        'total_price' => 149.99,
    ]);

    $response = $this->actingAs($customer)->get('/profile');

    $response->assertOk()
        ->assertSee('ORD-TEST-999')
        ->assertSee('$149.99')
        ->assertSee('Premium Wireless Headphones');
});

test('admin is redirected to admin profile when visiting /profile', function () {
    /** @var User $admin */
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->get('/profile');

    $response->assertRedirect(route('admin.profile'));
});

test('vendor is redirected to vendor profile when visiting /profile', function () {
    /** @var User $vendor */
    $vendor = User::factory()->create([
        'role' => 'vendor',
    ]);

    $response = $this->actingAs($vendor)->get('/profile');

    $response->assertRedirect(route('vendor.profile'));
});

test('customer can update their profile information through api', function () {
    /** @var User $customer */
    $customer = User::factory()->create([
        'name' => 'Initial Name',
        'phone' => '1111111111',
        'address' => 'Old Address 123',
    ]);

    $response = $this->actingAs($customer)->patchJson('/api/profile', [
        'name' => 'Updated Customer Name',
        'phone' => '9999999999',
        'address' => 'New Boulevard 456',
    ]);

    $response->assertOk()
        ->assertJson([
            'message' => 'Profile updated successfully',
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $customer->id,
        'name' => 'Updated Customer Name',
        'phone' => '9999999999',
        'address' => 'New Boulevard 456',
    ]);
});

test('user model relations work as expected', function () {
    /** @var User $customer */
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $product = Product::factory()->create();

    $order = Order::factory()->create(['user_id' => $customer->id]);
    $wishlist = Wishlist::factory()->create(['user_id' => $customer->id, 'product_id' => $product->id]);
    $cart = Cart::factory()->create(['user_id' => $customer->id, 'product_id' => $product->id]);
    $review = Review::factory()->create(['user_id' => $customer->id, 'product_id' => $product->id]);

    expect($customer->orders)->toHaveCount(1)
        ->and($customer->orders->first()->id)->toBe($order->id)
        ->and($customer->wishlists)->toHaveCount(1)
        ->and($customer->wishlists->first()->id)->toBe($wishlist->id)
        ->and($customer->carts)->toHaveCount(1)
        ->and($customer->carts->first()->id)->toBe($cart->id)
        ->and($customer->reviews)->toHaveCount(1)
        ->and($customer->reviews->first()->id)->toBe($review->id);
});
