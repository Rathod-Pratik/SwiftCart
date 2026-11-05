<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $status,
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
        $statusLower = strtolower($this->status);

        $title = match ($statusLower) {
            'completed', 'delivered' => 'Order Completed 🎉',
            'cancelled' => 'Order Cancelled ⚠️',
            'returned' => 'Order Returned 📦',
            default => 'Order Status: '.ucfirst($this->status),
        };

        $description = match ($statusLower) {
            'completed', 'delivered' => "Order #{$this->order->order_number} has been completed and marked as delivered.",
            'cancelled' => "Order #{$this->order->order_number} was cancelled.",
            'returned' => "Order #{$this->order->order_number} was returned.",
            default => "Order #{$this->order->order_number} status was updated to ".ucfirst($this->status).'.',
        };

        return [
            'title' => $title,
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->status,
            'description' => $description,
            'category' => 'order',
            'priority' => in_array($statusLower, ['cancelled', 'returned'], true) ? 'high' : 'normal',
            'action_url' => route('vendor.order.details').'?_id='.$this->order->id,
            'action_label' => 'View Order',
        ];
    }
}

