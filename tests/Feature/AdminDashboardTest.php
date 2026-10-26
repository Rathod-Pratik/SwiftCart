<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected when attempting to access the admin dashboard', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));
});

test('admin can view dashboard with stats cards and diagrams', function () {
    /** @var User $admin */
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertSee('Total Revenue');
    $response->assertSee('Total Orders');
    $response->assertSee('Registered Users');
    $response->assertSee('Active Vendors');
    $response->assertSee('revenue-sales-chart');
    $response->assertSee('order-status-chart');
    $response->assertSee('category-distribution-chart');
});

test('admin can fetch dashboard stats via API endpoint', function () {
    /** @var User $admin */
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($admin)->getJson('/api/admin/dashboard/stats');

    $response->assertSuccessful();
    $response->assertJsonStructure([
        'stats' => [
            'total_revenue',
            'total_revenue_formatted',
            'total_orders',
            'total_customers',
            'total_products',
            'total_categories',
            'approved_vendors',
            'pending_vendors',
        ],
        'charts' => [
            'timeline_labels',
            'revenue_series',
            'orders_series',
            'order_status_series',
            'order_status_labels',
            'category_labels',
            'category_series',
        ],
    ]);
});

test('admin navigation pages do not contain full screen preloader overlay', function () {
    /** @var User $admin */
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);

    $categoryResponse = $this->actingAs($admin)->get(route('admin.category'));
    $categoryResponse->assertSuccessful();
    $categoryResponse->assertDontSee('id="global-preloader"', false);
    $categoryResponse->assertDontSee('fixed inset-0 z-50', false);

    $productResponse = $this->actingAs($admin)->get(route('admin.product'));
    $productResponse->assertSuccessful();
    $productResponse->assertDontSee('id="global-preloader"', false);
    $productResponse->assertDontSee('fixed inset-0 z-50', false);

    $userResponse = $this->actingAs($admin)->get(route('admin.user'));
    $userResponse->assertSuccessful();
    $userResponse->assertSee('id="user-loading"', false);
    $userResponse->assertDontSee('id="global-preloader"', false);

    $requestDetailsResponse = $this->actingAs($admin)->get(route('admin.vendor.request.details', ['_id' => 1]));
    $requestDetailsResponse->assertSuccessful();
    $requestDetailsResponse->assertSee('Vendor Application');
    $requestDetailsResponse->assertSee('btn-approve');
    $requestDetailsResponse->assertSee('btn-reject');
    $requestDetailsResponse->assertDontSee('id="global-preloader"', false);
});
