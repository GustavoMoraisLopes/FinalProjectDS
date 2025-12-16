<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    /**
     * Determine whether the user can view the reservation.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->isAdmin() || $reservation->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the reservation.
     */
    public function update(User $user, Reservation $reservation): bool
    {
        // Admins can update; owners can update only if still pending
        if ($user->isAdmin()) {
            return true;
        }
        return $reservation->user_id === $user->id && $reservation->status === 'pending';
    }

    /**
     * Determine whether the user can delete the reservation.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        // Admins or owners can cancel while pending
        if ($user->isAdmin()) {
            return true;
        }
        return $reservation->user_id === $user->id && $reservation->status === 'pending';
    }
}
