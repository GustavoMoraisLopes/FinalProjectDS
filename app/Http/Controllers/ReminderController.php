<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            $notification->delete();
        }

        return redirect()->back()->with('success', 'Notificação eliminada.');
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('notification_ids', []);

        if (!empty($ids)) {
            auth()->user()
                ->notifications()
                ->whereIn('id', $ids)
                ->delete();

            $count = count($ids);
            return redirect()->back()->with('success', ($count === 1 ? '1 notificação' : "$count notificações") . ' eliminada(s) com sucesso.');
        }

        return redirect()->back();
    }

    public function deleteAll()
    {
        $count = auth()->user()->notifications()->count();
        auth()->user()->notifications()->delete();

        return redirect()->back()->with('success', 'Todas as notificações foram eliminadas.');
    }
}
