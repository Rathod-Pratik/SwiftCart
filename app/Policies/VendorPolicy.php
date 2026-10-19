<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;

class VendorPolicy
{
    /**
     * Determine if the user can apply to become a vendor.
     */
    public function apply(User $user): bool
    {
        return $user->vendor === null;
    }

    /**
     * Determine if the user can view the vendor profile.
     */
    public function view(User $user, Vendor $vendor): bool
    {
        return $user->isAdmin() || $user->id === $vendor->user_id;
    }

    /**
     * Determine if the user can update the vendor status (approve/reject).
     */
    public function updateStatus(User $user, Vendor $vendor): bool
    {
        // Only authorized admins can approve or reject, and admins cannot approve their own application.
        return $user->isAdmin() && $user->id !== $vendor->user_id;
    }

    /**
     * Determine if the user can download verification documents.
     */
    public function downloadDocument(User $user, Vendor $vendor): bool
    {
        return $user->isAdmin() || $user->id === $vendor->user_id;
    }
}
