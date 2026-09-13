<?php

namespace App\Observers;

use App\Models\Cart;
use Illuminate\Support\Facades\Cache;

class CartObserver
{
    public function created(Cart $cart): void
    {
        $this->flushCache($cart);
    }

    public function updated(Cart $cart): void
    {
        $this->flushCache($cart);
    }

    public function deleted(Cart $cart): void
    {
        $this->flushCache($cart);
    }

    private function flushCache(Cart $cart): void
    {
        Cache::tags(["carts:user:{$cart->user_id}"])->flush();
    }
}
