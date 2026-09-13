<?php

namespace App\Observers;

use App\Models\Review;
use Illuminate\Support\Facades\Cache;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $this->flushCache($review);
    }

    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        $this->flushCache($review);
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        $this->flushCache($review);
    }

    /**
     * Flush cached review listings for this review's product,
     * plus the "all reviews" (unfiltered) listing.
     */
    private function flushCache(Review $review): void
    {
        Cache::tags([
            "reviews:product:{$review->product_id}",
            'reviews:all',
        ])->flush();
    }
}
