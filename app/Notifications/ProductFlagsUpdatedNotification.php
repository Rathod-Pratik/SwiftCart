<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductFlagsUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * @param  array<string, bool>  $flags
     */
    public function __construct(
        public string $productName,
        public array $flags,
        public ?int $productId = null,
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
        $tags = [];
        if (! empty($this->flags['is_featured'])) {
            $tags[] = 'Featured';
        }
        if (! empty($this->flags['is_trending'])) {
            $tags[] = 'Trending';
        }
        if (! empty($this->flags['is_limited'])) {
            $tags[] = 'Limited Stock';
        }

        $tagStr = empty($tags) ? 'Promotional flags cleared' : 'Active: '.implode(', ', $tags);

        return [
            'title' => 'Product Badges Updated',
            'product_name' => $this->productName,
            'flags' => $this->flags,
            'description' => "Promotional flags updated for \"{$this->productName}\" ({$tagStr}).",
            'category' => 'inventory',
            'priority' => 'normal',
            'action_url' => $this->productId ? route('admin.product.details').'?_id='.$this->productId : route('admin.product'),
            'action_label' => 'View Product',
        ];
    }
}

