<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductRemovedAdminNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $productName,
        public string $reason,
        public ?string $vendorName = null,
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
        $vendorSnippet = $this->vendorName ? " (Vendor: {$this->vendorName})" : '';

        return [
            'title' => 'Product Removed by Admin',
            'product_name' => $this->productName,
            'reason' => $this->reason,
            'description' => "Product \"{$this->productName}\"{$vendorSnippet} was removed from the marketplace. Reason: {$this->reason}.",
            'category' => 'inventory',
            'priority' => 'high',
            'action_url' => route('admin.product'),
            'action_label' => 'View Catalog',
        ];
    }
}

