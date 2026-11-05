<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CouponActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $couponCode,
        public string $action = 'created',
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
            'created' => 'Coupon Created',
            'updated' => 'Coupon Updated',
            'deleted' => 'Coupon Removed',
            default => 'Coupon Notice',
        };

        $description = match ($this->action) {
            'created' => "Discount coupon \"{$this->couponCode}\" has been published and is active for buyers.",
            'updated' => "Discount coupon \"{$this->couponCode}\" was updated successfully.",
            'deleted' => "Discount coupon \"{$this->couponCode}\" was deleted.",
            default => "Coupon \"{$this->couponCode}\" {$this->action}.",
        };

        return [
            'title' => $title,
            'coupon_code' => $this->couponCode,
            'action' => $this->action,
            'description' => $description,
            'category' => 'coupon',
            'priority' => 'normal',
            'action_url' => route('vendor.coupon'),
            'action_label' => 'Manage Coupons',
        ];
    }
}

