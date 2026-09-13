<?php

namespace App\Observers;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Cache;

class WishlistObserver
{
    public function created(Wishlist $wishlist): void
    {
        $this->flushCache($wishlist);
    }

    public function updated(Wishlist $wishlist): void
    {
        $this->flushCache($wishlist);
    }

    public function deleted(Wishlist $wishlist): void
    {
        $this->flushCache($wishlist);
    }

    private function flushCache(Wishlist $wishlist): void
    {
        Cache::tags(["wishlists:user:{$wishlist->user_id}"])->flush();
    }
}
