<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewProductReviewReceived extends Notification
{
    use Queueable;

    public function __construct(
        public Product $product,
        public Review $review,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $commentSnippet = $this->review->comment ? ' - "'.Str::limit($this->review->comment, 60).'"' : '';

        return [
            'title' => 'New Review on '.$this->product->name,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'rating' => $this->review->rating,
            'description' => "Buyer left a {$this->review->rating}★ review on \"{$this->product->name}\"{$commentSnippet}.",
            'category' => 'product',
            'priority' => $this->review->rating <= 2 ? 'high' : 'normal',
            'action_url' => route('vendor.product.details').'?_id='.$this->product->id,
            'action_label' => 'View Product',
        ];
    }
}

