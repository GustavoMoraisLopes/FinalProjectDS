<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Reservation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEquipments = Equipment::count();
        $availableEquipments = Equipment::where('status', 'available')->count();
        $loanedEquipments = Equipment::where('status', 'loaned')->count();
        $maintenanceEquipments = Equipment::where('status', 'maintenance')->count();

        $recentReservations = Reservation::with(['equipment', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $myActiveReservations = Reservation::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->count();

        $pendingApprovals = Reservation::where('status', 'pending')->count();

        return view('dashboard', compact(
            'totalEquipments',
            'availableEquipments',
            'loanedEquipments',
            'maintenanceEquipments',
            'recentReservations',
            'myActiveReservations',
            'pendingApprovals'
        ));
    }
}
