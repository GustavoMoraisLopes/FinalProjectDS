<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservationApprovedNotification extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'icon' => 'check-circle',
            'color' => 'success',
            'title' => 'Requisição Aprovada',
            'message' => 'A sua requisição do equipamento "' . $this->reservation->equipment->name . '" foi aprovada.',
            'action_url' => route('reservations.show', $this->reservation->id),
            'action_text' => 'Ver requisição',
            'reservation_id' => $this->reservation->id,
        ];
    }
}
