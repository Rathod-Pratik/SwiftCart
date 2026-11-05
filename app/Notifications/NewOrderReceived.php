<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderReceived extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order,
        public int $itemCount,
        public float $vendorTotal,
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
        return [
            'title' => 'New Order Received',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'description' => "You received order #{$this->order->order_number} ({$this->itemCount} item(s) totalling ₹".number_format($this->vendorTotal, 2).').',
            'category' => 'order',
            'priority' => 'high',
            'action_url' => route('vendor.order.details').'?_id='.$this->order->id,
            'action_label' => 'View Order',
        ];
    }
}

