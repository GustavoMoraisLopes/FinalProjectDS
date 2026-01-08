<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Notifications\ReturnReminderNotification;

class ReminderController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);

        $unreadCount = auth()->user()->unreadNotifications->count();

        return view('reminders.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    }

    public function delete($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            if ($this->isLockedReturnReminder($notification)) {
                return redirect()->back()->with('error', 'Não é possível eliminar este lembrete de devolução enquanto a requisição aguarda devolução do equipamento.');
            }
            $notification->delete();
        }

        return redirect()->back()->with('success', 'Notificação eliminada.');
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('notification_ids', []);

        if (!empty($ids)) {
            $userNotifications = auth()->user()->notifications()->whereIn('id', $ids)->get();
            $locked = $userNotifications->filter(fn($n) => $this->isLockedReturnReminder($n));
            $allowed = $userNotifications->reject(fn($n) => $this->isLockedReturnReminder($n));

            if ($allowed->count() > 0) {
                auth()->user()
                    ->notifications()
                    ->whereIn('id', $allowed->pluck('id'))
                    ->delete();
            }

            $deletedCount = $allowed->count();
            $lockedCount = $locked->count();

            if ($deletedCount > 0) {
                return redirect()->back()->with(
                    'success',
                    ($deletedCount === 1 ? '1 notificação' : "$deletedCount notificações") . ' eliminada(s) com sucesso.' .
                    ($lockedCount > 0 ? " {$lockedCount} lembrete(s) de devolução não foi/foram removido(s) porque aguardam devolução." : '')
                );
            }

            return redirect()->back()->with('error', 'Nenhuma notificação foi eliminada. Os lembretes de devolução pendentes mantêm-se até a devolução do equipamento.');
        }

        return redirect()->back();
    }

    public function deleteAll()
    {
        $userNotifications = auth()->user()->notifications()->get();
        $locked = $userNotifications->filter(fn($n) => $this->isLockedReturnReminder($n));
        $allowed = $userNotifications->reject(fn($n) => $this->isLockedReturnReminder($n));

        if ($allowed->count() > 0) {
            auth()->user()->notifications()->whereIn('id', $allowed->pluck('id'))->delete();
        }

        $msg = [];
        if ($allowed->count() > 0) {
            $msg[] = ($allowed->count() === 1 ? '1 notificação' : $allowed->count() . ' notificações') . ' eliminada(s).';
        }
        if ($locked->count() > 0) {
            $msg[] = $locked->count() . ' lembrete(s) de devolução não foi/foram removido(s) porque aguardam devolução.';
        }

        return redirect()->back()->with('success', implode(' ', $msg) ?: 'Sem notificações para eliminar.');
    }

    /**
     * Bloqueia exclusão de lembretes de devolução enquanto a requisição não estiver devolvida.
     */
    private function isLockedReturnReminder($notification): bool
    {
        // Só bloqueia se for uma notificação de devolução
        if ($notification->type !== ReturnReminderNotification::class) {
            return false;
        }

        // Extrai o ID da requisição dos dados da notificação
        $reservationId = $notification->data['reservation_id'] ?? null;
        if (!$reservationId) {
            return false;
        }

        // Busca a requisição e verifica seu status
        $reservation = Reservation::find($reservationId);

        // Bloqueia se a requisição existe E não está completada
        return $reservation && $reservation->status !== 'completed';
    }
}
