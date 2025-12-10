<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Equipment;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['equipment', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $equipment_id = $request->query('equipment_id');
        $equipments = Equipment::where('status', 'available')->get();

        return view('reservations.create', compact('equipments', 'equipment_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'purpose' => 'nullable|string|max:255',
            'project' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Reservation::create($validated);

        return redirect()->route('reservations.index')->with('success', 'Requisição criada com sucesso! Aguardando aprovação.');
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $this->authorize('update', $reservation);
        $equipments = Equipment::all();
        return view('reservations.edit', compact('reservation', 'equipments'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.index')->with('success', 'Requisição atualizada!');
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);
        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Requisição cancelada!');
    }

    public function checkout(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $reservation->update([
            'status' => 'approved',
            'checked_out_at' => now(),
        ]);

        $reservation->equipment->update(['status' => 'loaned']);

        return redirect()->route('reservations.show', $reservation)->with('success', 'Equipamento requisitado com sucesso!');
    }

    public function checkin(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $reservation->update([
            'status' => 'completed',
            'checked_in_at' => now(),
        ]);

        $reservation->equipment->update(['status' => 'available']);

        return redirect()->route('reservations.show', $reservation)->with('success', 'Equipamento devolvido com sucesso!');
    }
}
