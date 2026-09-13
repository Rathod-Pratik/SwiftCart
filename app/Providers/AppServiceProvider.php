<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use App\Observers\CartObserver;
use App\Observers\CategoryObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\ReviewObserver;
use App\Observers\WishlistObserver;
use App\Policies\CartPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ConversationPolicy;
use App\Policies\CouponPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\UserPolicy;
use App\Policies\WishlistPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cart::observe(CartObserver::class);
        Wishlist::observe(WishlistObserver::class);
        Category::observe(CategoryObserver::class);
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
        Review::observe(ReviewObserver::class);

        Gate::policy(Cart::class, CartPolicy::class);
        Gate::policy(Conversation::class, ConversationPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Wishlist::class, WishlistPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Coupon::class, CouponPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
