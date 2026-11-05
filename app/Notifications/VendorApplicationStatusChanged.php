<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VendorApplicationStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public Vendor $vendor,
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
        $isApproved = $this->status === 'approved';

        return [
            'title' => $isApproved ? 'Seller Account Approved! 🎉' : 'Seller Application Status',
            'status' => $this->status,
            'description' => $isApproved
                ? "Congratulations! Your store \"{$this->vendor->store_name}\" has been approved. You can now start adding products and managing orders."
                : "Your seller application for \"{$this->vendor->store_name}\" was not approved. Please review requirements and reapply.",
            'category' => 'system',
            'priority' => 'high',
            'action_url' => $isApproved ? route('vendor.dashboard') : route('vendor.register'),
            'action_label' => $isApproved ? 'Go to Dashboard' : 'View Application',
        ];
    }
}

