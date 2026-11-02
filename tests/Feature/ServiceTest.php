<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('vendor can view services index including standard perks and their own', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);

    // Standard global perk
    Service::create([
        'vendor_id' => null,
        'name' => 'Cash on Delivery',
        'icon' => 'banknote',
        'description' => 'Pay at doorstep',
        'is_active' => true,
    ]);

    // Vendor's custom perk
    Service::create([
        'vendor_id' => $vendor->id,
        'name' => 'Custom Express 24h',
        'icon' => 'truck',
        'description' => 'Ultra fast delivery',
        'is_active' => true,
    ]);

    $response = $this->actingAs($vendor)->getJson('/api/vendor/services');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonFragment(['name' => 'Cash on Delivery'])
        ->assertJsonFragment(['name' => 'Custom Express 24h']);
});

test('vendor can create a custom service', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);

    $response = $this->actingAs($vendor)->postJson('/api/vendor/services', [
        'name' => 'Cash on Delivery',
        'icon' => 'banknote',
        'description' => 'Pay cash when your shipment arrives.',
        'is_active' => true,
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Service created successfully',
            'data' => [
                'name' => 'Cash on Delivery',
                'icon' => 'banknote',
                'vendor_id' => $vendor->id,
            ],
        ]);

    $this->assertDatabaseHas('services', [
        'name' => 'Cash on Delivery',
        'icon' => 'banknote',
        'vendor_id' => $vendor->id,
    ]);
});

test('service creation validates required name and icon', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);

    $response = $this->actingAs($vendor)->postJson('/api/vendor/services', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'icon']);
});

test('vendor can update their own service', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);

    $service = Service::create([
        'vendor_id' => $vendor->id,
        'name' => 'Original Name',
        'icon' => 'truck',
        'description' => 'Old description',
        'is_active' => true,
    ]);

    $response = $this->actingAs($vendor)->putJson("/api/vendor/services/{$service->id}", [
        'name' => 'Express Shipping Plus',
        'icon' => 'zap',
        'description' => 'Super fast shipment within 12 hours.',
        'is_active' => true,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Service updated successfully',
            'data' => [
                'name' => 'Express Shipping Plus',
                'icon' => 'zap',
            ],
        ]);

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'name' => 'Express Shipping Plus',
        'icon' => 'zap',
    ]);
});

test('vendor service edit data is loaded from the database', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);

    $service = Service::create([
        'vendor_id' => $vendor->id,
        'name' => 'Database Service',
        'icon' => 'shield-check',
        'description' => 'Stored service description',
        'is_active' => false,
    ]);

    $this->actingAs($vendor)->getJson("/api/vendor/services/{$service->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Database Service')
        ->assertJsonPath('data.icon', 'shield-check')
        ->assertJsonPath('data.description', 'Stored service description')
        ->assertJsonPath('data.is_active', false);
});

test('vendor cannot update another vendor service', function () {
    /** @var TestCase $this */
    /** @var User $vendor1 */
    $vendor1 = User::factory()->create(['role' => 'vendor']);
    /** @var User $vendor2 */
    $vendor2 = User::factory()->create(['role' => 'vendor']);

    $service = Service::create([
        'vendor_id' => $vendor1->id,
        'name' => 'Vendor 1 Service',
        'icon' => 'box',
    ]);

    $response = $this->actingAs($vendor2)->putJson("/api/vendor/services/{$service->id}", [
        'name' => 'Hacked Name',
        'icon' => 'zap',
    ]);

    $response->assertStatus(403);
});

test('vendor can delete their own service', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);

    $service = Service::create([
        'vendor_id' => $vendor->id,
        'name' => 'Service to Delete',
        'icon' => 'box',
    ]);

    $response = $this->actingAs($vendor)->deleteJson("/api/vendor/services/{$service->id}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Service deleted successfully',
        ]);

    $this->assertDatabaseMissing('services', [
        'id' => $service->id,
    ]);
});

test('vendor cannot delete another vendor service', function () {
    /** @var TestCase $this */
    /** @var User $vendor1 */
    $vendor1 = User::factory()->create(['role' => 'vendor']);
    /** @var User $vendor2 */
    $vendor2 = User::factory()->create(['role' => 'vendor']);

    $service = Service::create([
        'vendor_id' => $vendor1->id,
        'name' => 'Vendor 1 Service',
        'icon' => 'box',
    ]);

    $response = $this->actingAs($vendor2)->deleteJson("/api/vendor/services/{$service->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('services', [
        'id' => $service->id,
    ]);
});

test('vendor can update and customize a standard service', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);

    $standardService = Service::create([
        'vendor_id' => null,
        'name' => 'Cash on Delivery',
        'icon' => 'banknote',
        'description' => 'Pay at doorstep',
        'is_active' => true,
    ]);

    $response = $this->actingAs($vendor)->putJson("/api/vendor/services/{$standardService->id}", [
        'name' => 'Cash on Delivery (Verified)',
        'icon' => 'banknote',
        'description' => 'Pay cash securely after checking items.',
        'is_active' => true,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Service updated successfully',
            'data' => [
                'name' => 'Cash on Delivery (Verified)',
                'vendor_id' => $vendor->id,
            ],
        ]);
});

test('vendor can delete a standard service from their view', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create(['role' => 'vendor']);

    $standardService = Service::create([
        'vendor_id' => null,
        'name' => 'Unwanted Standard Perk',
        'icon' => 'box',
    ]);

    $response = $this->actingAs($vendor)->deleteJson("/api/vendor/services/{$standardService->id}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Service deleted successfully',
        ]);

    $this->assertDatabaseMissing('services', [
        'id' => $standardService->id,
    ]);
});

test('guest cannot manage services', function () {
    /** @var TestCase $this */
    $response = $this->getJson('/api/vendor/services');
    $response->assertStatus(401);

    $createResponse = $this->postJson('/api/vendor/services', [
        'name' => 'Test Service',
        'icon' => 'truck',
    ]);
    $createResponse->assertStatus(401);
});

test('vendor can view service web pages', function () {
    /** @var TestCase $this */
    /** @var User $vendor */
    $vendor = User::factory()->create([
        'role' => 'vendor',
        'email_verified_at' => now(),
    ]);

    $indexRes = $this->actingAs($vendor)->get('/vendor/service');
    $indexRes->assertStatus(200);

    $createRes = $this->actingAs($vendor)->get('/vendor/service/create');
    $createRes->assertStatus(200);
});
