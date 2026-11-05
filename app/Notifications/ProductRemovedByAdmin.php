<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductRemovedByAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $productName,
        public string $reason,
        public string $description,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * @return array<string, string>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Product removed by admin',
            'product_name' => $this->productName,
            'reason' => $this->reason,
            'description' => $this->description,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your SwiftCart product was removed')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your product "'.$this->productName.'" was removed by an administrator.')
            ->line('Reason: '.$this->reason)
            ->line('Description: '.$this->description)
            ->line('Please contact SwiftCart support if you need more information.');
    }
}
