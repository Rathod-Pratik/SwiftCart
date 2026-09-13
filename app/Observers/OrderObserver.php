<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $this->flushCache($order);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        $this->flushCache($order);
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        $this->flushCache($order);
    }

    /**
     * Flush cached order listings for this order's user.
     */
    private function flushCache(Order $order): void
    {
        Cache::tags(["orders:user:{$order->user_id}"])->flush();
    }
}
