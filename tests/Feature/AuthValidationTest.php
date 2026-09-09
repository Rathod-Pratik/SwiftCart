<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('signup returns custom validation error messages for required fields', function () {
    $response = $this->postJson('/api/signup', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name' => 'Name is required.',
            'email' => 'Email address is required.',
            'password' => 'Password is required.',
        ]);
});

test('signup returns custom validation error messages for min/max/confirmed rules', function () {
    $response = $this->postJson('/api/signup', [
        'name' => 'a',
        'email' => 'invalid-email',
        'password' => 'short',
        'password_confirmation' => 'mismatch',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name' => 'Name must be at least 2 characters.',
            'email' => 'Please enter a valid email address.',
            'password' => 'Password must be at least 8 characters long.',
        ]);
});

test('signup registers a new user successfully', function () {
    $response = $this->postJson('/api/signup', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'User registered successfully',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);
});

test('login returns custom validation errors on empty payload', function () {
    $response = $this->postJson('/api/login', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'email' => 'Email address is required.',
            'password' => 'Password is required.',
        ]);
});

test('delete account requires current password and removes account successfully', function () {
    $user = User::factory()->create([
        'password' => 'Secret123!',
    ]);

    // Validation error when password is missing
    $responseMissing = $this->actingAs($user)->deleteJson('/api/profile', []);
    $responseMissing->assertStatus(422)
        ->assertJsonValidationErrors([
            'password' => 'Password is required to delete your account.',
        ]);

    // Validation error when password is wrong
    $responseWrong = $this->actingAs($user)->deleteJson('/api/profile', [
        'password' => 'WrongPassword',
    ]);
    $responseWrong->assertStatus(422)
        ->assertJsonValidationErrors([
            'password' => 'The provided password does not match your current password.',
        ]);

    // Success when correct password is provided
    $responseSuccess = $this->actingAs($user)->deleteJson('/api/profile', [
        'password' => 'Secret123!',
    ]);
    $responseSuccess->assertStatus(200)
        ->assertJson([
            'message' => 'Account deleted successfully',
        ]);

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

test('update profile returns custom validation messages', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patchJson('/api/profile', [
        'name' => 'A',
        'email' => 'invalid-email',
        'phone' => '123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name' => 'Name must be at least 2 characters.',
            'email' => 'Please enter a valid email address.',
            'phone' => 'Phone number must be at least 10 digits.',
        ]);
});
