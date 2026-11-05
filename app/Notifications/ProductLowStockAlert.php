<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductLowStockAlert extends Notification
{
    use Queueable;

    public function __construct(
        public Product $product,
        public int $remainingStock,
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
        $isOutOfStock = $this->remainingStock <= 0;

        return [
            'title' => $isOutOfStock ? 'Product Out of Stock!' : 'Low Stock Warning',
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'remaining_stock' => $this->remainingStock,
            'description' => $isOutOfStock
                ? "Your product \"{$this->product->name}\" is completely out of stock. Update your inventory to keep selling."
                : "Your product \"{$this->product->name}\" is running low on stock ({$this->remainingStock} units remaining).",
            'category' => 'product',
            'priority' => 'high',
            'action_url' => route('vendor.product'),
            'action_label' => 'Update Stock',
        ];
    }
}

