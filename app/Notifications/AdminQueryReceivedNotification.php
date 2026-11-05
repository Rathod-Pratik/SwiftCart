<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class AdminQueryReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Conversation $conversation,
        public Message $message,
        public User $sender,
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
        $body = $this->message->body
            ? Str::limit($this->message->body, 70)
            : ($this->message->attachment_path ? 'Sent an attachment' : 'New support inquiry');

        return [
            'title' => 'Customer Support Query: '.$this->sender->name,
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'description' => "Query from {$this->sender->name}: \"{$body}\"",
            'category' => 'query',
            'priority' => 'high',
            'action_url' => route('admin.query.details').'?_id='.$this->conversation->id,
            'action_label' => 'View Query',
        ];
    }
}

