<?php

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('index returns list of reviews with pagination', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = Product::factory()->create();

    Review::factory()->count(3)->create([
        'product_id' => $product->id,
    ]);

    $response = $this->getJson('/api/reviews');

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Reviews fetched successfully',
        ])
        ->assertJsonCount(3, 'data.data');
});

test('index filters reviews by product_id', function () {
    /** @var TestCase $this */
    /** @var Product $product1 */
    $product1 = Product::factory()->create();

    /** @var Product $product2 */
    $product2 = Product::factory()->create();

    Review::factory()->count(2)->create(['product_id' => $product1->id]);
    Review::factory()->count(3)->create(['product_id' => $product2->id]);

    $response = $this->getJson("/api/reviews?product_id={$product1->id}");

    $response->assertSuccessful()
        ->assertJsonCount(2, 'data.data');
});

test('index returns 404 when no reviews found', function () {
    /** @var TestCase $this */
    $response = $this->getJson('/api/reviews');

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'No reviews found',
        ]);
});

test('index validates product_id exists if provided', function () {
    /** @var TestCase $this */
    $response = $this->getJson('/api/reviews?product_id=999999');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['product_id']);
});

test('index validates pagination parameters', function () {
    /** @var TestCase $this */
    $response = $this->getJson('/api/reviews?page=invalid&per_page=200');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['page', 'per_page']);
});

test('store creates a review successfully', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/reviews', [
        'product_id' => $product->id,
        'rating' => 5,
        'comment' => 'Excellent product, highly recommended!',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Review created successfully',
        ]);

    $this->assertDatabaseHas('reviews', [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'rating' => 5,
        'comment' => 'Excellent product, highly recommended!',
    ]);
});

test('store creates a review with images uploaded', function () {
    /** @var TestCase $this */
    Storage::fake('s3');

    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    $image1 = UploadedFile::fake()->image('review1.jpg');
    $image2 = UploadedFile::fake()->image('review2.png');

    $response = $this->actingAs($user)->postJson('/api/reviews', [
        'product_id' => $product->id,
        'rating' => 4,
        'comment' => 'Great product with photos!',
        'images' => [$image1, $image2],
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Review created successfully',
        ]);

    $review = Review::where('user_id', $user->id)->first();
    expect($review)->not->toBeNull();
    expect($review->images)->toBeArray();
    expect(count($review->images))->toBe(2);
});

test('store validates required fields', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/reviews', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['product_id', 'rating']);
});

test('store validates product_id must exist in products table', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/reviews', [
        'product_id' => 999999,
        'rating' => 4,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['product_id']);
});

test('store validates rating bounds (between 1 and 5)', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    $responseMin = $this->actingAs($user)->postJson('/api/reviews', [
        'product_id' => $product->id,
        'rating' => 0,
    ]);

    $responseMin->assertStatus(422)
        ->assertJsonValidationErrors(['rating']);

    $responseMax = $this->actingAs($user)->postJson('/api/reviews', [
        'product_id' => $product->id,
        'rating' => 6,
    ]);

    $responseMax->assertStatus(422)
        ->assertJsonValidationErrors(['rating']);
});

test('store validates images max count and file type', function () {
    /** @var TestCase $this */
    Storage::fake('s3');

    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    // 6 images exceeds max 5
    $images = [
        UploadedFile::fake()->image('1.jpg'),
        UploadedFile::fake()->image('2.jpg'),
        UploadedFile::fake()->image('3.jpg'),
        UploadedFile::fake()->image('4.jpg'),
        UploadedFile::fake()->image('5.jpg'),
        UploadedFile::fake()->image('6.jpg'),
    ];

    $response = $this->actingAs($user)->postJson('/api/reviews', [
        'product_id' => $product->id,
        'rating' => 4,
        'images' => $images,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['images']);

    // Non-image file
    $notAnImage = UploadedFile::fake()->create('document.pdf', 100);

    $responseNonImage = $this->actingAs($user)->postJson('/api/reviews', [
        'product_id' => $product->id,
        'rating' => 4,
        'images' => [$notAnImage],
    ]);

    $responseNonImage->assertStatus(422)
        ->assertJsonValidationErrors(['images.0']);
});

test('store validates comment maximum character limit', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/reviews', [
        'product_id' => $product->id,
        'rating' => 4,
        'comment' => str_repeat('a', 1001),
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['comment']);
});

test('store requires authentication', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = Product::factory()->create();

    $response = $this->postJson('/api/reviews', [
        'product_id' => $product->id,
        'rating' => 5,
    ]);

    $response->assertStatus(401);
});

test('update modifies an existing review successfully', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Product $product */
    $product = Product::factory()->create();

    /** @var Review $review */
    $review = Review::factory()->create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'rating' => 3,
        'comment' => 'Initial comment',
    ]);

    $response = $this->actingAs($user)->patchJson("/api/reviews/{$review->id}", [
        'rating' => 5,
        'comment' => 'Updated comment after further testing',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Review updated successfully',
        ]);

    $this->assertDatabaseHas('reviews', [
        'id' => $review->id,
        'rating' => 5,
        'comment' => 'Updated comment after further testing',
    ]);
});

test('update rejects unauthorized user', function () {
    /** @var TestCase $this */
    /** @var User $owner */
    $owner = User::factory()->create();

    /** @var User $otherUser */
    $otherUser = User::factory()->create();

    /** @var Review $review */
    $review = Review::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($otherUser)->patchJson("/api/reviews/{$review->id}", [
        'rating' => 1,
    ]);

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'You are not authorized to update this review',
        ]);
});

test('update validates rating when provided', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Review $review */
    $review = Review::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patchJson("/api/reviews/{$review->id}", [
        'rating' => 10,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['rating']);
});

test('destroy deletes review successfully by owner', function () {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Review $review */
    $review = Review::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->deleteJson("/api/reviews/{$review->id}");

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Review deleted successfully',
        ]);

    $this->assertDatabaseMissing('reviews', [
        'id' => $review->id,
    ]);
});

test('destroy allows admin to delete any review', function () {
    /** @var TestCase $this */
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    /** @var User $user */
    $user = User::factory()->create(['role' => 'user']);

    /** @var Review $review */
    $review = Review::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($admin)->deleteJson("/api/reviews/{$review->id}");

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Review deleted successfully',
        ]);

    $this->assertDatabaseMissing('reviews', [
        'id' => $review->id,
    ]);
});

test('destroy rejects unauthorized user', function () {
    /** @var TestCase $this */
    /** @var User $owner */
    $owner = User::factory()->create();

    /** @var User $otherUser */
    $otherUser = User::factory()->create();

    /** @var Review $review */
    $review = Review::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($otherUser)->deleteJson("/api/reviews/{$review->id}");

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'You are not authorized to delete this review',
        ]);

    $this->assertDatabaseHas('reviews', [
        'id' => $review->id,
    ]);
});

test('destroy requires authentication', function () {
    /** @var TestCase $this */
    /** @var Review $review */
    $review = Review::factory()->create();

    $response = $this->deleteJson("/api/reviews/{$review->id}");

    $response->assertStatus(401);
});
