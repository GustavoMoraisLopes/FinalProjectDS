<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProfileUpdatedNotification extends Notification
{
    use Queueable;

    protected $field;
    protected $oldValue;
    protected $newValue;

    /**
     * Create a new notification instance.
     */
    public function __construct($field = 'Perfil', $oldValue = null, $newValue = null)
    {
        $this->field = $field;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
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
            'icon' => 'person-check',
            'color' => 'success',
            'title' => 'Perfil Atualizado com Sucesso',
            'message' => 'O seu ' . $this->field . ' foi alterado com sucesso.',
            'timestamp' => now()->format('d/m/Y H:i'),
        ];
    }
}
