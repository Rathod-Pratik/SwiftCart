<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->flushCache($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->flushCache($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->flushCache($product);
    }

    /**
     * Flush cached product listings and this product's individual cache.
     */
    private function flushCache(Product $product): void
    {
        // Flushes ALL index listings (since visibility/filters could affect any of them)
        // and this specific product's show() cache
        Cache::tags(['products', "product:{$product->id}"])->flush();
    }
}
