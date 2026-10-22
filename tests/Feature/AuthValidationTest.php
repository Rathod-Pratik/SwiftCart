<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\S3Service;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Mockery\MockInterface;
use RuntimeException;

use function Pest\Laravel\mock;

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

    Notification::fake();

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

    $user = User::where('email', 'john@example.com')->firstOrFail();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('web signup redirects with validation errors', function () {

    $response = $this->from('/register')->post('/register', []);

    $response->assertRedirect('/register')
        ->assertSessionHasErrors([
            'name' => 'Name is required.',
            'email' => 'Email address is required.',
            'password' => 'Password is required.',
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

test('web login endpoint accepts post requests', function () {

    $response = $this->postJson('/login', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'email' => 'Email address is required.',
            'password' => 'Password is required.',
        ]);
});

test('web login redirects with validation errors', function () {

    $response = $this->from('/login')->post('/login', []);

    $response->assertRedirect('/login')
        ->assertSessionHasErrors([
            'email' => 'Email address is required.',
            'password' => 'Password is required.',
        ]);
});

test('delete account requires current password and removes account successfully', function () {

    /** @var User $user */
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

    /** @var User $user */
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

test('update profile does not require an image', function () {

    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patchJson('/api/profile', [
        'name' => 'Updated Name',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'message' => 'Profile updated successfully',
        ]);

    expect($user->fresh()->name)->toBe('Updated Name');
});

test('update profile hides upload exceptions from the client', function () {

    /** @var MockInterface $s3ServiceMock */
    $s3ServiceMock = mock(S3Service::class);
    $s3ServiceMock
        ->shouldReceive('generateKey')
        ->once()
        ->andReturn('profile-images/1/avatar.png')
        ->shouldReceive('uploadFromServer')
        ->once()
        ->andThrow(new RuntimeException('private storage failure'));

    $user = User::factory()->create();

    $response = $this->actingAs($user)->patchJson('/api/profile', [
        'image' => UploadedFile::fake()->image('avatar.png'),
    ]);

    $response->assertStatus(500)
        ->assertJson([
            'message' => 'Error occurred while updating profile.',
        ])
        ->assertJsonMissing([
            'message' => 'private storage failure',
        ]);
});

test('login with remember me sets remember token and authenticates', function () {
    $user = User::factory()->create([
        'email' => 'rememberme@example.com',
        'password' => 'Password123!',
        'email_verified_at' => now(),
    ]);

    $response = $this->post('/login', [
        'email' => 'rememberme@example.com',
        'password' => 'Password123!',
        'remember' => '1',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertCookie(\Illuminate\Support\Facades\Auth::guard()->getRecallerName());
});

