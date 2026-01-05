<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification
{
    use Queueable;

    protected $ipAddress;
    protected $userAgent;

    /**
     * Create a new notification instance.
     */
    public function __construct($ipAddress = null, $userAgent = null)
    {
        $this->ipAddress = $ipAddress ?? request()->ip();
        $this->userAgent = $userAgent ?? request()->userAgent();
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
            'icon' => 'box-arrow-in-right',
            'color' => 'info',
            'title' => 'Novo Acesso',
            'message' => 'Acesso realizado de ' . ($this->ipAddress ?? 'endereco desconhecido'),
            'timestamp' => now()->format('d/m/Y H:i'),
        ];
    }
}
