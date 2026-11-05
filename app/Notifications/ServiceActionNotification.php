<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ServiceActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $serviceName,
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
            'created' => 'Service Perk Added',
            'updated' => 'Service Perk Updated',
            'deleted' => 'Service Perk Removed',
            default => 'Service Perk Notice',
        };

        $description = match ($this->action) {
            'created' => "Service perk \"{$this->serviceName}\" has been created and can now be offered on your products.",
            'updated' => "Service perk \"{$this->serviceName}\" was updated successfully.",
            'deleted' => "Service perk \"{$this->serviceName}\" was deleted.",
            default => "Service perk \"{$this->serviceName}\" {$this->action}.",
        };

        return [
            'title' => $title,
            'service_name' => $this->serviceName,
            'action' => $this->action,
            'description' => $description,
            'category' => 'service',
            'priority' => 'normal',
            'action_url' => route('vendor.service'),
            'action_label' => 'Manage Services',
        ];
    }
}

