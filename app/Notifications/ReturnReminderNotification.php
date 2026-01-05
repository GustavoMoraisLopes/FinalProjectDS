<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReturnReminderNotification extends Notification
{
    use Queueable;

    protected $reservation;
    protected $daysLeft;

    public function __construct(Reservation $reservation, $daysLeft = 1)
    {
        $this->reservation = $reservation;
        $this->daysLeft = $daysLeft;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $message = $this->daysLeft == 0 
            ? 'O equipamento "' . $this->reservation->equipment->name . '" deve ser devolvido hoje!' 
            : 'Falta' . ($this->daysLeft == 1 ? '' : 'm') . ' ' . $this->daysLeft . ' dia' . ($this->daysLeft == 1 ? '' : 's') . ' para devolver o equipamento "' . $this->reservation->equipment->name . '".';

        return [
            'icon' => 'clock',
            'color' => $this->daysLeft == 0 ? 'danger' : 'warning',
            'title' => 'Lembrete de Devolução',
            'message' => $message,
            'action_url' => route('reservations.show', $this->reservation->id),
            'action_text' => 'Ver requisição',
            'reservation_id' => $this->reservation->id,
        ];
    }
}
