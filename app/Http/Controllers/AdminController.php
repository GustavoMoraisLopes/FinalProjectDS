<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Services\AuditLogger;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $recentLogs = AuditLog::with('user')->orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.index', compact('totalUsers', 'adminUsers', 'recentLogs'));
    }

    public function users(Request $request)
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::all();

        return view('admin.logs', compact('logs', 'users'));
    }

    public function approveTeacher($userId)
    {
        $user = User::findOrFail($userId);

        // Verificar se tem pedido pendente
        if (!$user->hasPendingTeacherRequest()) {
            return redirect()->route('admin.users')->with('error', 'Este utilizador não tem pedido pendente.');
        }

        // Atualizar user_type e limpar flag
        $user->update([
            'user_type' => 'teacher',
            'teacher_request_pending' => false,
        ]);

        // Audit log
        AuditLogger::log('user_promoted_to_teacher', $user, 'Utilizador ' . $user->name . ' promovido a professor');

        // Notificar utilizador (opcional: implementar notificação)
        // $user->notify(new TeacherAccessApprovedNotification());

        return redirect()->route('admin.users')->with('success', 'Utilizador ' . $user->name . ' aprovado como professor!');
    }

    public function rejectTeacher($userId)
    {
        $user = User::findOrFail($userId);

        if (!$user->hasPendingTeacherRequest()) {
            return redirect()->route('admin.users')->with('error', 'Este utilizador não tem pedido pendente.');
        }

        $user->update([
            'teacher_request_pending' => false,
        ]);

        AuditLogger::log('user_teacher_request_rejected', $user, 'Pedido de professor rejeitado para ' . $user->name);

        return redirect()->route('admin.users')->with('success', 'Pedido de professor rejeitado para ' . $user->name . '.');
    }
}
