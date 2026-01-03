<?php

namespace App\Notifications;

use App\Models\Mod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ModUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Mod $mod;
    public string $version;

    /**
     * Create a new notification instance.
     */
    public function __construct(Mod $mod, string $version)
    {
        $this->mod = $mod;
        $this->version = $version;
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
            'mod_id' => $this->mod->id,
            'title' => $this->mod->title,
            'slug' => $this->mod->slug,
            'version' => $this->version,
            'message' => "New version v{$this->version} available for {$this->mod->title}",
            'type' => 'mod_update',
        ];
    }
}
