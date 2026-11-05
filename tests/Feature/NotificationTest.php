<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use App\Models\Vendor;
use App\Notifications\AdminQueryReceivedNotification;
use App\Notifications\CategoryActionNotification;
use App\Notifications\CouponActionNotification;
use App\Notifications\NewCustomerQueryReceived;
use App\Notifications\NewOrderReceived;
use App\Notifications\NewProductReviewReceived;
use App\Notifications\OrderStatusUpdated;
use App\Notifications\ProductActionNotification;
use App\Notifications\ProductFlagsUpdatedNotification;
use App\Notifications\ProductRemovedAdminNotification;
use App\Notifications\ProductRemovedByAdmin;
use App\Notifications\ServiceActionNotification;
use App\Notifications\VendorApplicationStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('vendor navbar renders notification icon linking to notification page with unread badge', function () {
    $vendor = User::factory()->vendor()->create();

    $this->actingAs($vendor)
        ->get(route('vendor.dashboard'))
        ->assertSuccessful()
        ->assertSee('id="vendor-notification-btn"', false)
        ->assertSee('href="'.route('vendor.notification').'"', false)
        ->assertSee('id="vendor-nav-unread-badge"', false)
        ->assertDontSee('animate-pulse', false);
});

test('vendor notifications page renders successfully', function () {
    $vendor = User::factory()->vendor()->create();

    $this->actingAs($vendor)
        ->get(route('vendor.notification'))
        ->assertSuccessful()
        ->assertSee('Vendor Notification Center')
        ->assertSee('id="notifications-container"', false)
        ->assertSee('id="notification-search"', false);
});

test('authenticated user can fetch notifications from api', function () {
    $vendor = User::factory()->vendor()->create();

    $this->actingAs($vendor)
        ->getJson('/api/notifications')
        ->assertSuccessful()
        ->assertJsonStructure([
            'success',
            'data',
            'unread_count',
        ]);
});

test('authenticated user can fetch unread count from api', function () {
    $vendor = User::factory()->vendor()->create();

    $this->actingAs($vendor)
        ->getJson('/api/notifications/unread-count')
        ->assertSuccessful()
        ->assertJsonPath('success', true);
});

test('unauthenticated user cannot fetch notifications', function () {
    $this->getJson('/api/notifications')
        ->assertUnauthorized();
});

test('authenticated user can trigger mark-all-read', function () {
    $vendor = User::factory()->vendor()->create();

    $this->actingAs($vendor)
        ->postJson('/api/notifications/mark-all-read')
        ->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonPath('unread_count', 0);
});

test('order creation dispatches notification to product vendors', function () {
    Notification::fake();

    $customer = User::factory()->customer()->create();
    $vendor = User::factory()->vendor()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
        'price' => 200,
    ]);

    $this->actingAs($customer)
        ->postJson('/api/orders', [
            'payment_method' => 'cod',
            'shipping_name' => 'John Doe',
            'shipping_phone' => '9876543210',
            'shipping_address' => '123 Main St',
            'shipping_city' => 'Mumbai',
            'shipping_state' => 'Maharashtra',
            'shipping_postal_code' => '400001',
            'shipping_country' => 'India',
            'items' => [
                [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => 200,
                    'quantity' => 2,
                    'total_price' => 400,
                ],
            ],
        ])
        ->assertCreated();

    Notification::assertSentTo($vendor, NewOrderReceived::class, function (NewOrderReceived $notif) use ($product) {
        return $notif->itemCount === 2 && $notif->vendorTotal === 400.0;
    });
});

test('review creation dispatches notification to the product vendor', function () {
    Notification::fake();

    $customer = User::factory()->customer()->create();
    $vendor = User::factory()->vendor()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
    ]);

    $this->actingAs($customer)
        ->postJson('/api/reviews', [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Excellent build quality and fast shipping!',
        ])
        ->assertCreated();

    Notification::assertSentTo($vendor, NewProductReviewReceived::class, function (NewProductReviewReceived $notif) use ($product) {
        return $notif->product->id === $product->id && $notif->review->rating === 5;
    });
});

test('message creation dispatches notification to other conversation participant', function () {
    Notification::fake();

    $customer = User::factory()->customer()->create();
    $vendor = User::factory()->vendor()->create();
    $conversation = Conversation::create([
        'customer_id' => $customer->id,
        'vendor_id' => $vendor->id,
        'type' => 'customer_vendor',
    ]);

    $this->actingAs($customer)
        ->postJson("/api/conversations/{$conversation->id}/messages", [
            'body' => 'Hello vendor, do you have this in blue?',
        ])
        ->assertCreated();

    Notification::assertSentTo($vendor, NewCustomerQueryReceived::class, function (NewCustomerQueryReceived $notif) use ($conversation, $customer) {
        return $notif->conversation->id === $conversation->id && $notif->sender->id === $customer->id;
    });
});

test('admin approving vendor application dispatches status notification', function () {
    Notification::fake();

    $admin = User::factory()->admin()->create();
    $applicant = User::factory()->create();
    $vendor = Vendor::factory()->pending()->create([
        'user_id' => $applicant->id,
        'store_name' => 'Super Retailers',
    ]);

    $this->actingAs($admin)
        ->patchJson("/api/vendor-applications/{$vendor->id}/status", [
            'status' => 'approved',
        ])
        ->assertSuccessful();

    Notification::assertSentTo($applicant, VendorApplicationStatusChanged::class, function (VendorApplicationStatusChanged $notif) {
        return $notif->status === 'approved';
    });
});

test('service actions dispatch service notification', function () {
    Notification::fake();

    $vendor = User::factory()->vendor()->create();

    $this->actingAs($vendor)
        ->postJson('/api/vendor/services', [
            'name' => 'Fast Delivery',
            'icon' => 'truck',
            'description' => 'Fast express shipping perk',
            'is_active' => true,
        ])
        ->assertCreated();

    Notification::assertSentTo($vendor, ServiceActionNotification::class, function (ServiceActionNotification $notif) {
        return $notif->serviceName === 'Fast Delivery' && $notif->action === 'created';
    });
});

test('coupon actions dispatch coupon notification', function () {
    Notification::fake();

    $vendor = User::factory()->vendor()->create();

    $this->actingAs($vendor)
        ->postJson('/api/vendor/coupons', [
            'code' => 'SAVE50',
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'expires_at' => now()->addDays(10)->toDateString(),
            'is_active' => true,
        ])
        ->assertCreated();

    Notification::assertSentTo($vendor, CouponActionNotification::class, function (CouponActionNotification $notif) {
        return $notif->couponCode === 'SAVE50' && $notif->action === 'created';
    });
});

test('order status update dispatches status notification with proper state', function () {
    Notification::fake();

    $customer = User::factory()->customer()->create();
    $vendor = User::factory()->vendor()->create();
    $order = Order::factory()->for($customer)->create([
        'order_status' => 'pending',
    ]);

    $this->actingAs($customer)
        ->patchJson("/api/orders/{$order->id}/status", [
            'status' => 'cancelled',
        ])
        ->assertSuccessful();

    Notification::assertSentTo($customer, OrderStatusUpdated::class, function (OrderStatusUpdated $notif) {
        return $notif->status === 'cancelled';
    });
});

test('admin navbar renders notification bell and unread badge linking to notification page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee('id="admin-notification-btn"', false)
        ->assertSee('href="'.route('admin.notification').'"', false)
        ->assertSee('id="nav-unread-badge"', false);
});

test('admin notification center page renders successfully', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.notification'))
        ->assertSuccessful()
        ->assertSee('Notification Center')
        ->assertSee('id="notifications-container"', false)
        ->assertSee('id="notification-search"', false);
});

test('admin updating product promotional flags dispatches flags notification', function () {
    Notification::fake();

    $admin = User::factory()->admin()->create();
    $vendor = User::factory()->vendor()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
    ]);

    $this->actingAs($admin)
        ->patchJson("/api/admin/products/{$product->id}/flags", [
            'is_trending' => true,
            'is_featured' => true,
            'is_limited' => false,
        ])
        ->assertSuccessful();

    Notification::assertSentTo($admin, ProductFlagsUpdatedNotification::class, function (ProductFlagsUpdatedNotification $notif) use ($product) {
        return $notif->productName === $product->name &&
            $notif->flags['is_trending'] === true &&
            $notif->flags['is_featured'] === true;
    });
});

test('admin removing product dispatches notification to vendor and admins', function () {
    Notification::fake();

    $admin = User::factory()->admin()->create();
    $vendor = User::factory()->vendor()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
    ]);

    $this->actingAs($admin)
        ->deleteJson("/api/admin/products/{$product->id}", [
            'reason' => 'Counterfeit notice',
            'description' => 'The brand copyright holder reported this listing.',
        ])
        ->assertSuccessful();

    Notification::assertSentTo($vendor, ProductRemovedByAdmin::class, function (ProductRemovedByAdmin $notif) use ($product) {
        return $notif->productName === $product->name && $notif->reason === 'Counterfeit notice';
    });

    Notification::assertSentTo($admin, ProductRemovedAdminNotification::class, function (ProductRemovedAdminNotification $notif) use ($product) {
        return $notif->productName === $product->name && $notif->reason === 'Counterfeit notice';
    });
});

test('admin deleting category dispatches notification to admins', function () {
    Storage::fake('s3');
    Notification::fake();

    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create([
        'name' => 'Home Appliances',
    ]);

    $this->actingAs($admin)
        ->deleteJson("/api/categories/{$category->id}")
        ->assertSuccessful();

    Notification::assertSentTo($admin, CategoryActionNotification::class, function (CategoryActionNotification $notif) {
        return $notif->categoryName === 'Home Appliances' && $notif->action === 'deleted';
    });
});

test('customer message in customer_admin conversation dispatches notification to admins', function () {
    Notification::fake();

    $admin = User::factory()->admin()->create();
    $customer = User::factory()->customer()->create();
    $conversation = Conversation::create([
        'customer_id' => $customer->id,
        'type' => 'customer_admin',
    ]);

    $this->actingAs($customer)
        ->postJson("/api/conversations/{$conversation->id}/messages", [
            'body' => 'Need help with payment checkout issue',
        ])
        ->assertCreated();

    Notification::assertSentTo($admin, AdminQueryReceivedNotification::class, function (AdminQueryReceivedNotification $notif) use ($conversation, $customer) {
        return $notif->conversation->id === $conversation->id && $notif->sender->id === $customer->id;
    });
});

test('authenticated user can trigger mark single notification as unread', function () {
    $vendor = User::factory()->vendor()->create();

    // Directly create a notification in database
    $notification = $vendor->notifications()->create([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'type' => ProductActionNotification::class,
        'data' => [
            'title' => 'Test Notification',
            'description' => 'Test description',
            'category' => 'product',
        ],
        'read_at' => now(),
    ]);

    $this->actingAs($vendor)
        ->patchJson("/api/notifications/{$notification->id}/unread")
        ->assertSuccessful()
        ->assertJsonPath('success', true);

    expect($vendor->unreadNotifications()->count())->toBe(1);
});
