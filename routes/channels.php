<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (! $conversation) {
        return false;
    }

    // Only the customer, the vendor, or the admin involved can listen
    return in_array($user->id, [
        $conversation->customer_id,
        $conversation->vendor_id,
        $conversation->admin_id,
    ]);
});
