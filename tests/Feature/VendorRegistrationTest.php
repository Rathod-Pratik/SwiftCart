<?php

namespace Tests\Feature;

use App\Mail\VendorAccountApproved;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('guest users cannot submit vendor registration', function () {
    $response = $this->postJson('/api/vendor-register', [
        'store_name' => 'Swift Store',
        'phone' => '1234567890',
        'business_address' => '123 Market Street',
        'business_type' => 'individual',
    ]);

    $response->assertStatus(401);
});

test('guest users are prompted to login or sign up on the registration page', function () {
    $response = $this->get(route('vendor.register'));

    $response->assertSuccessful()
        ->assertSee('Become a SwiftCart Seller')
        ->assertSee('Sign in to Apply')
        ->assertSee(route('login'))
        ->assertSee(route('register'));
});

test('footer and navbar contain links to become a seller', function () {
    $this->get('/')
        ->assertSuccessful()
        ->assertSee('Become a Seller')
        ->assertSee(route('vendor.register'));
});

test('authenticated user can successfully submit a vendor application with pending status by default', function () {
    Storage::fake('public');
    Storage::fake('local');

    /** @var User $user */
    $user = User::factory()->create(['role' => 'customer']);

    $logo = UploadedFile::fake()->image('store_logo.png', 400, 400);
    $document = UploadedFile::fake()->create('business_doc.pdf', 500, 'application/pdf');

    $response = $this->actingAs($user)->postJson('/api/vendor-register', [
        'store_name' => 'Pratik Electronics',
        'phone' => '9876543210',
        'business_address' => '456 Commercial Avenue',
        'business_type' => 'registered business',
        'description' => 'Top consumer gadgets and electronics.',
        'business_registration_number' => 'REG-998877',
        'tax_identification_number' => 'TAX-112233',
        'logo' => $logo,
        'verification_document' => $document,
        'status' => 'approved', // Attempting to self-approve should be ignored!
        'role' => 'admin', // Attempting to escalate role should be ignored!
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'Vendor application submitted successfully and is pending approval.',
        ]);

    $this->assertDatabaseHas('vendors', [
        'user_id' => $user->id,
        'store_name' => 'Pratik Electronics',
        'business_type' => 'registered business',
        'status' => 'pending', // Must be pending, NOT approved
    ]);

    $vendor = Vendor::query()->where('user_id', $user->id)->firstOrFail();
    expect($vendor->status)->toBe('pending');

    // User role must remain customer until approved
    expect($user->fresh()->role)->toBe('customer');

    // Verify logo and sensitive document storage
    expect($vendor->logo)->not->toBeNull();
    Storage::disk('public')->assertExists($vendor->logo);

    expect($vendor->verification_document)->not->toBeNull();
    Storage::disk('local')->assertExists($vendor->verification_document);
});

test('user cannot create multiple vendor profiles', function () {
    /** @var User $user */
    $user = User::factory()->create(['role' => 'customer']);

    Vendor::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)->postJson('/api/vendor-register', [
        'store_name' => 'Second Store Attempt',
        'phone' => '9876543210',
        'business_address' => '789 Trade Street',
        'business_type' => 'individual',
    ]);

    $response->assertStatus(403);
    expect(Vendor::where('user_id', $user->id)->count())->toBe(1);
});

test('validation rejects missing required fields and invalid business types', function () {
    /** @var User $user */
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->postJson('/api/vendor-register', [
        'store_name' => '',
        'phone' => '',
        'business_address' => '',
        'business_type' => 'unsupported_type',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'store_name',
            'phone',
            'business_address',
            'business_type',
        ]);
});

test('validation enforces file types and max size limits for logo and documents', function () {
    Storage::fake('public');
    Storage::fake('local');

    /** @var User $user */
    $user = User::factory()->create(['role' => 'customer']);

    // Logo must be an image, max 2MB
    $invalidLogo = UploadedFile::fake()->create('logo.txt', 100, 'text/plain');
    $oversizedDoc = UploadedFile::fake()->create('large_doc.pdf', 6000, 'application/pdf'); // > 5MB

    $response = $this->actingAs($user)->postJson('/api/vendor-register', [
        'store_name' => 'Store Name',
        'phone' => '9876543210',
        'business_address' => '123 Market Street',
        'business_type' => 'individual',
        'logo' => $invalidLogo,
        'verification_document' => $oversizedDoc,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['logo', 'verification_document']);
});

test('only authorized admins can approve vendor applications', function () {
    /** @var User $customer */
    $customer = User::factory()->create(['role' => 'customer']);

    /** @var User $applicant */
    $applicant = User::factory()->create(['role' => 'customer']);
    $vendor = Vendor::factory()->create([
        'user_id' => $applicant->id,
        'status' => 'pending',
    ]);

    // Customer tries to approve -> 403 Forbidden
    $responseCustomer = $this->actingAs($customer)->patchJson("/api/vendor-applications/{$vendor->id}/status", [
        'status' => 'approved',
    ]);
    $responseCustomer->assertStatus(403);

    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    // Admin approves -> 200 OK
    $responseAdmin = $this->actingAs($admin)->patchJson("/api/vendor-applications/{$vendor->id}/status", [
        'status' => 'approved',
    ]);

    $responseAdmin->assertStatus(200)
        ->assertJson([
            'message' => 'Vendor application approved successfully.',
        ]);

    expect($vendor->fresh()->status)->toBe('approved')
        ->and($applicant->fresh()->role)->toBe('vendor');
});

test('admin cannot approve their own vendor application', function () {
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    $vendor = Vendor::factory()->create([
        'user_id' => $admin->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($admin)->patchJson("/api/vendor-applications/{$vendor->id}/status", [
        'status' => 'approved',
    ]);

    $response->assertStatus(403);
    expect($vendor->fresh()->status)->toBe('pending');
});

test('admin can reject vendor application and role remains customer', function () {
    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    /** @var User $applicant */
    $applicant = User::factory()->create(['role' => 'customer']);
    $vendor = Vendor::factory()->create([
        'user_id' => $applicant->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($admin)->patchJson("/api/vendor-applications/{$vendor->id}/status", [
        'status' => 'rejected',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Vendor application rejected successfully.',
        ]);

    expect($vendor->fresh()->status)->toBe('rejected')
        ->and($applicant->fresh()->role)->toBe('customer');
});

test('only approved vendors can create products', function () {
    $category = Category::factory()->create();

    // 1. Regular customer cannot create products
    /** @var User $customer */
    $customer = User::factory()->create(['role' => 'customer']);
    $this->actingAs($customer)->postJson('/api/products', [
        'name' => 'Product by Customer',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id,
        'features' => 'features',
    ])->assertStatus(403);

    // 2. Pending vendor cannot create products
    /** @var User $pendingUser */
    $pendingUser = User::factory()->create(['role' => 'vendor']);
    Vendor::factory()->create([
        'user_id' => $pendingUser->id,
        'status' => 'pending',
    ]);

    $this->actingAs($pendingUser)->postJson('/api/products', [
        'name' => 'Product by Pending Vendor',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id,
        'features' => 'features',
    ])->assertStatus(403);

    // 3. Rejected vendor cannot create products
    /** @var User $rejectedUser */
    $rejectedUser = User::factory()->create(['role' => 'vendor']);
    Vendor::factory()->create([
        'user_id' => $rejectedUser->id,
        'status' => 'rejected',
    ]);

    $this->actingAs($rejectedUser)->postJson('/api/products', [
        'name' => 'Product by Rejected Vendor',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id,
        'features' => 'features',
    ])->assertStatus(403);

    // 4. Approved vendor CAN create products
    /** @var User $approvedUser */
    $approvedUser = User::factory()->create(['role' => 'vendor']);
    Vendor::factory()->create([
        'user_id' => $approvedUser->id,
        'status' => 'approved',
    ]);

    $response = $this->actingAs($approvedUser)->postJson('/api/products', [
        'name' => 'Approved Vendor Product',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id,
        'features' => 'High quality features',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'Product created successfully',
        ]);

    $this->assertDatabaseHas('products', [
        'name' => 'Approved Vendor Product',
        'vendor_id' => $approvedUser->id,
    ]);
});

test('sensitive verification documents are restricted to admin or owner', function () {
    Storage::fake('local');
    $filePath = Storage::disk('local')->putFile('verification_documents', UploadedFile::fake()->create('doc.pdf'));

    /** @var User $owner */
    $owner = User::factory()->create(['role' => 'vendor']);
    $vendor = Vendor::factory()->create([
        'user_id' => $owner->id,
        'verification_document' => $filePath,
    ]);

    /** @var User $otherUser */
    $otherUser = User::factory()->create(['role' => 'customer']);

    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    // Another user cannot download -> 403
    $this->actingAs($otherUser)->get("/api/vendors/{$vendor->id}/verification-document")
        ->assertStatus(403);

    // Owner can download -> 200
    $this->actingAs($owner)->get("/api/vendors/{$vendor->id}/verification-document")
        ->assertSuccessful();

    // Admin can download -> 200
    $this->actingAs($admin)->get("/api/vendors/{$vendor->id}/verification-document")
        ->assertSuccessful();
});

test('laravel relationships: User has one Vendor, Vendor belongs to User, Vendor has many Products', function () {
    /** @var User $user */
    $user = User::factory()->create(['role' => 'vendor']);

    /** @var Vendor $vendor */
    $vendor = Vendor::factory()->create([
        'user_id' => $user->id,
        'status' => 'approved',
    ]);

    $category = Category::factory()->create();

    $product1 = Product::factory()->create([
        'vendor_id' => $user->id,
        'category_id' => $category->id,
    ]);
    $product2 = Product::factory()->create([
        'vendor_id' => $user->id,
        'category_id' => $category->id,
    ]);

    // User has one Vendor
    expect($user->vendor)->not->toBeNull()
        ->and($user->vendor->id)->toBe($vendor->id);

    // Vendor belongs to User
    expect($vendor->user)->not->toBeNull()
        ->and($vendor->user->id)->toBe($user->id);

    // Vendor has many Products
    expect($vendor->products)->toHaveCount(2)
        ->and($vendor->products->pluck('id')->all())->toEqualCanonicalizing([$product1->id, $product2->id]);
});

test('admin accepting vendor request sends VendorAccountApproved email', function () {
    Mail::fake();

    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    /** @var User $applicant */
    $applicant = User::factory()->create(['role' => 'customer']);

    /** @var Vendor $vendor */
    $vendor = Vendor::factory()->create([
        'user_id' => $applicant->id,
        'store_name' => 'Swift Gadgets',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($admin)->patchJson("/api/vendor-applications/{$vendor->id}/status", [
        'status' => 'approved',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Vendor application approved successfully.',
        ]);

    Mail::assertQueued(VendorAccountApproved::class, function (VendorAccountApproved $mail) use ($applicant, $vendor) {
        return $mail->hasTo($applicant->email)
            && $mail->vendor->id === $vendor->id;
    });
});

test('admin rejecting vendor request does not send VendorAccountApproved email', function () {
    Mail::fake();

    /** @var User $admin */
    $admin = User::factory()->create(['role' => 'admin']);

    /** @var User $applicant */
    $applicant = User::factory()->create(['role' => 'customer']);

    /** @var Vendor $vendor */
    $vendor = Vendor::factory()->create([
        'user_id' => $applicant->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($admin)->patchJson("/api/vendor-applications/{$vendor->id}/status", [
        'status' => 'rejected',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Vendor application rejected successfully.',
        ]);

    Mail::assertNothingQueued();
});

test('vendor account approved email contains product publishing, bank details, and required documents instructions', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'name' => 'Jane Seller',
        'email' => 'jane.seller@example.com',
        'phone' => '9876543210',
    ]);

    /** @var Vendor $vendor */
    $vendor = Vendor::factory()->create([
        'user_id' => $user->id,
        'store_name' => 'Jane Boutique',
        'status' => 'approved',
    ]);

    $mailable = new VendorAccountApproved($vendor);

    $mailable->assertHasSubject('Vendor Account Approved - Start Selling on SwiftCart');
    $mailable->assertSeeInHtml('Congratulations! Your Vendor Account is Ready');
    $mailable->assertSeeInHtml('Jane Boutique');
    $mailable->assertSeeInHtml('Start Publishing Your Products');
    $mailable->assertSeeInHtml('Action Required: Update Bank Details for Payouts');
    $mailable->assertSeeInHtml('Required Verification Documents');
    $mailable->assertSeeInHtml(route('vendor.product.create'));
    $mailable->assertSeeInHtml(route('vendor.profile'));
    $mailable->assertSeeInHtml('Identity Proof');
    $mailable->assertSeeInHtml('Business Certificate');
});
