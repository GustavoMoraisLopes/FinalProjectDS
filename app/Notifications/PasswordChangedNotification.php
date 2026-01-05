<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
    public function toDatabase(object $notifiable): array
    {
        return [
            'icon' => 'shield-lock',
            'color' => 'warning',
            'title' => 'Segurança: Palavra-passe Alterada',
            'message' => 'A sua palavra-passe foi alterada com sucesso. Se não foi você, altere imediatamente.',
            'timestamp' => now()->format('d/m/Y H:i'),
        ];
    }
}
