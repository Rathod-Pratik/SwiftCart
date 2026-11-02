<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('store returns custom validation messages for required fields', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->postJson('/api/coupons', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'code' => 'The coupon code is required.',
            'discount_amount' => 'The discount amount is required.',
            'discount_type' => 'The discount type is required.',
        ]);
});

test('store rejects invalid coupon field values', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->postJson('/api/coupons', [
        'code' => 'SAVE10',
        'discount_amount' => 'not-a-number',
        'discount_type' => 'invalid',
        'expires_at' => 'not-a-date',
        'is_active' => 'not-a-boolean',
        'number_of_coupons' => 0,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'discount_amount',
            'discount_type',
            'expires_at',
            'is_active',
            'number_of_coupons',
        ]);
});

test('store rejects duplicate coupon codes', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Coupon::factory()->create(['code' => 'SAVE10']);

    $response = $this->actingAs($admin)->postJson('/api/coupons', [
        'code' => 'SAVE10',
        'discount_amount' => 10,
        'discount_type' => 'percentage',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'code' => 'The coupon code must be unique.',
        ]);
});

test('store creates a coupon with valid data', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->postJson('/api/coupons', [
        'code' => 'SAVE10',
        'discount_amount' => 10,
        'discount_type' => 'percentage',
        'expires_at' => '2030-01-01',
        'is_active' => true,
        'number_of_coupons' => 5,
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Coupon created successfully',
        ]);

    $this->assertDatabaseHas('coupons', [
        'code' => 'SAVE10',
        'discount_amount' => 10,
        'discount_type' => 'percentage',
        'is_active' => true,
        'number_of_coupons' => 5,
    ]);
});

test('vendors can manage coupons through the vendor coupon API', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);

    $createResponse = $this->actingAs($vendor)->postJson('/api/vendor/coupons', [
        'code' => 'VENDOR10',
        'discount_type' => 'percentage',
        'discount_value' => 10,
        'expires_at' => '2030-01-01',
        'usage_limit' => 25,
        'is_active' => true,
    ]);

    $createResponse->assertCreated()
        ->assertJsonPath('data.code', 'VENDOR10')
        ->assertJsonPath('data.discount_value', 10)
        ->assertJsonPath('data.usage_limit', 25);

    $coupon = Coupon::where('code', 'VENDOR10')->firstOrFail();

    $this->actingAs($vendor)->getJson('/api/vendor/coupons')
        ->assertSuccessful()
        ->assertJsonPath('data.0.code', 'VENDOR10');

    $this->actingAs($vendor)->putJson("/api/vendor/coupons/{$coupon->id}", [
        'code' => 'VENDOR15',
        'discount_type' => 'percentage',
        'discount_value' => 15,
        'expires_at' => '2030-01-01',
        'usage_limit' => 30,
        'is_active' => false,
    ])->assertSuccessful()
        ->assertJsonPath('data.code', 'VENDOR15');

    $this->actingAs($vendor)->deleteJson("/api/vendor/coupons/{$coupon->id}")
        ->assertSuccessful();

    expect(Coupon::whereKey($coupon->id)->exists())->toBeFalse();
});

test('vendor coupon edit data is loaded from the database', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);
    $coupon = Coupon::factory()->create([
        'code' => 'DATABASE20',
        'discount_type' => 'percentage',
        'discount_amount' => 20,
        'description' => 'Database coupon description',
        'minimum_order_amount' => 100,
        'maximum_discount' => 50,
        'starts_at' => '2029-12-01 08:00:00',
        'expires_at' => '2030-01-01 12:00:00',
        'is_active' => false,
        'number_of_coupons' => 15,
        'per_user_limit' => 2,
    ]);

    $this->actingAs($vendor)->getJson("/api/vendor/coupons/{$coupon->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.code', 'DATABASE20')
        ->assertJsonPath('data.discount_type', 'percentage')
        ->assertJsonPath('data.discount_value', 20)
        ->assertJsonPath('data.description', 'Database coupon description')
        ->assertJsonPath('data.minimum_order_amount', '100.00')
        ->assertJsonPath('data.maximum_discount', '50.00')
        ->assertJsonPath('data.starts_at', '2029-12-01T08:00:00.000000Z')
        ->assertJsonPath('data.expires_at', '2030-01-01T12:00:00.000000Z')
        ->assertJsonPath('data.is_active', false)
        ->assertJsonPath('data.usage_limit', 15)
        ->assertJsonPath('data.per_user_limit', 2);
});
