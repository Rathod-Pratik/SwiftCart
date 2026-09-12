<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

// ---------------- INDEX ----------------

test('index returns categories successfully', function () {
    /** @var TestCase $this */
    Category::factory()->count(3)->create();

    $response = $this->getJson('/api/categories');

    $response->assertStatus(200)
        ->assertJson(['success' => true]);
});

test('index returns 404 when there are no categories', function () {
    /** @var TestCase $this */
    $response = $this->getJson('/api/categories');

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'No categories found',
        ]);
});

test('index rejects an invalid per_page value', function () {
    /** @var TestCase $this */
    Category::factory()->create();

    $response = $this->getJson('/api/categories?per_page=500');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['per_page']);
});

// ---------------- STORE ----------------

test('store returns validation errors when required fields are missing', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->postJson('/api/categories', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'description',
            'slug',
            'icon',
            'status',
        ]);
});

test('store rejects a duplicate slug', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);
    $existing = Category::factory()->create(['slug' => 'electronics']);

    $response = $this->actingAs($admin)->postJson('/api/categories', [
        'name' => 'New Electronics',
        'description' => 'A category',
        'slug' => 'electronics', // already taken
        'icon' => 'icon-electronics',
        'status' => 'active',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['slug']);
});

test('store rejects an invalid status value', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->postJson('/api/categories', [
        'name' => 'Test Category',
        'description' => 'A category',
        'slug' => 'test-category',
        'icon' => 'icon-test',
        'status' => 'archived', // not in allowed list
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});

test('store rejects fields exceeding max length', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->postJson('/api/categories', [
        'name' => str_repeat('a', 256),
        'description' => str_repeat('b', 256),
        'slug' => 'valid-slug',
        'icon' => str_repeat('c', 101),
        'status' => 'active',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'description', 'icon']);
});

test('store succeeds with valid data', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->postJson('/api/categories', [
        'name' => 'Electronics',
        'description' => 'Gadgets and devices',
        'slug' => 'electronics',
        'icon' => 'icon-electronics',
        'status' => 'active',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Category created successfully',
        ]);

    $this->assertDatabaseHas('categories', [
        'slug' => 'electronics',
    ]);
});

test('a non-admin user cannot create a category', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->postJson('/api/categories', [
        'name' => 'Electronics',
        'description' => 'Gadgets and devices',
        'slug' => 'electronics',
        'icon' => 'icon-electronics',
        'status' => 'active',
    ]);

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'You are not authorized to create category',
        ]);
});

// ---------------- UPDATE ----------------

test('update allows partial payloads without requiring all fields', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->patchJson("/api/categories/{$category->id}", [
        'name' => 'Updated Name Only',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Category updated successfully',
        ]);

    expect($category->fresh()->name)->toBe('Updated Name Only');
});

test('update still validates provided fields even though they are optional', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->patchJson("/api/categories/{$category->id}", [
        'status' => 'archived', // invalid
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});

test('update allows keeping the same slug on the same category', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create(['slug' => 'electronics']);

    $response = $this->actingAs($admin)->patchJson("/api/categories/{$category->id}", [
        'slug' => 'electronics', // same as its own current slug — should NOT trigger uniqueness error
    ]);

    $response->assertStatus(200);
});

test('update rejects a slug already used by a different category', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);
    Category::factory()->create(['slug' => 'fashion']);
    $category = Category::factory()->create(['slug' => 'electronics']);

    $response = $this->actingAs($admin)->patchJson("/api/categories/{$category->id}", [
        'slug' => 'fashion', // belongs to a different category
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['slug']);
});

test('a non-admin user cannot update a category', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create(['role' => 'user']);
    $category = Category::factory()->create();

    $response = $this->actingAs($user)->patchJson("/api/categories/{$category->id}", [
        'name' => 'Attempted Update',
    ]);

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'You are not authorized to update category',
        ]);
});

// ---------------- DESTROY ----------------

test('an admin can delete a category', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->deleteJson("/api/categories/{$category->id}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('a non-admin user cannot delete a category', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create(['role' => 'user']);
    $category = Category::factory()->create();

    $response = $this->actingAs($user)->deleteJson("/api/categories/{$category->id}");

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'You are not authorized to delete category',
        ]);
});
