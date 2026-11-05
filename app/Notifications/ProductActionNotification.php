<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $productName,
        public string $action = 'created',
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
        $title = match ($this->action) {
            'created' => 'Product Published',
            'updated' => 'Product Updated',
            'deleted' => 'Product Deleted',
            default => 'Product Notice',
        };

        $description = match ($this->action) {
            'created' => "Product \"{$this->productName}\" was created and published to your catalog.",
            'updated' => "Product \"{$this->productName}\" was successfully updated.",
            'deleted' => "Product \"{$this->productName}\" was removed from your store.",
            default => "Product \"{$this->productName}\" has been {$this->action}.",
        };

        $actionUrl = ($this->productId && $this->action !== 'deleted')
            ? route('vendor.product.details').'?_id='.$this->productId
            : route('vendor.product');

        return [
            'title' => $title,
            'product_name' => $this->productName,
            'action' => $this->action,
            'description' => $description,
            'category' => 'product',
            'priority' => 'normal',
            'action_url' => $actionUrl,
            'action_label' => 'View Catalog',
        ];
    }
}

