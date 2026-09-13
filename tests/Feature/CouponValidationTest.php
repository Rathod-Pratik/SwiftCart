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
