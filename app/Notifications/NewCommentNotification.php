<?php

namespace App\Notifications;

use App\Models\ModComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public ModComment $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(ModComment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'mod_id' => $this->comment->mod->id,
            'comment_id' => $this->comment->id,
            'user_name' => $this->comment->user->name,
            'mod_title' => $this->comment->mod->title,
            'message' => "{$this->comment->user->name} commented on {$this->comment->mod->title}",
            'type' => 'new_comment',
        ];
    }
}
