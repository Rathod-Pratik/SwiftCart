<?php

use App\Models\Conversation;
use App\Models\User;
use App\Policies\ConversationPolicy;

it('allows conversation participants to view and send messages', function () {
    $user = new User;
    $user->id = 10;
    $conversation = new Conversation([
        'customer_id' => 10,
        'vendor_id' => 20,
        'admin_id' => null,
    ]);

    $policy = new ConversationPolicy;

    expect($policy->view($user, $conversation))->toBeTrue()
        ->and($policy->sendMessage($user, $conversation))->toBeTrue();
});

it('denies non-participants access to a conversation', function () {
    $user = new User;
    $user->id = 30;
    $conversation = new Conversation([
        'customer_id' => 10,
        'vendor_id' => 20,
        'admin_id' => null,
    ]);

    $policy = new ConversationPolicy;

    expect($policy->view($user, $conversation))->toBeFalse()
        ->and($policy->sendMessage($user, $conversation))->toBeFalse();
});