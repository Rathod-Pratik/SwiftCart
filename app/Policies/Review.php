<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Order;

class ReviewPolicy
{

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && $order->isCompleted();
    }

    public function view(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }
}
