<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

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
}
