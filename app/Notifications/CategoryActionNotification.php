<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CategoryActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $categoryName,
        public string $action = 'created',
        public ?int $categoryId = null,
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
            'created' => 'Category Created',
            'updated' => 'Category Updated',
            'deleted' => 'Category Deleted',
            default => 'Category Notice',
        };

        $description = match ($this->action) {
            'created' => "Category \"{$this->categoryName}\" has been created successfully.",
            'updated' => "Category \"{$this->categoryName}\" details have been updated.",
            'deleted' => "Category \"{$this->categoryName}\" was removed from catalog.",
            default => "Category \"{$this->categoryName}\" was {$this->action}.",
        };

        return [
            'title' => $title,
            'category_name' => $this->categoryName,
            'action' => $this->action,
            'description' => $description,
            'category' => 'inventory',
            'priority' => 'normal',
            'action_url' => route('admin.category'),
            'action_label' => 'View Categories',
        ];
    }
}

