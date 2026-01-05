<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservationRejectedNotification extends Notification
{
    use Queueable;

    protected $reservation;
    protected $reason;

    public function __construct(Reservation $reservation, $reason = null)
    {
        $this->reservation = $reservation;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'icon' => 'x-circle',
            'color' => 'danger',
            'title' => 'Requisição Rejeitada',
            'message' => 'A sua requisição do equipamento "' . $this->reservation->equipment->name . '" foi rejeitada.' . ($this->reason ? ' Motivo: ' . $this->reason : ''),
            'action_url' => route('reservations.show', $this->reservation->id),
            'action_text' => 'Ver requisição',
            'reservation_id' => $this->reservation->id,
        ];
    }
}
