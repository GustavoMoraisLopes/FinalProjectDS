<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Services\AuditLogger;
use Carbon\Carbon;

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
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
            'purpose' => 'nullable|string|max:255',
            'project' => 'nullable|string|max:255',
        ]);

        $start = Carbon::createFromFormat('d/m/Y', $validated['start_date'])->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $validated['end_date'])->endOfDay();

        if ($start->lt(Carbon::today())) {
            return back()
                ->withErrors(['start_date' => 'A data inicial deve ser hoje ou posterior.'])
                ->withInput();
        }
        if ($end->lt($start)) {
            return back()
                ->withErrors(['end_date' => 'A data final deve ser posterior ou igual à inicial.'])
                ->withInput();
        }

        $validated['start_date'] = $start->toDateString();
        $validated['end_date'] = $end->toDateString();

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        $reservation = Reservation::create($validated);

        AuditLogger::log(
            'reservation.created',
            $reservation,
            'Requisição criada',
            null,
            [
                'equipment_id' => $reservation->equipment_id,
                'start_date' => $reservation->start_date,
                'end_date' => $reservation->end_date,
                'status' => $reservation->status,
            ]
        );

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

        $old = $reservation->only(array_keys($validated));
        $reservation->update($validated);
        $new = $reservation->only(array_keys($validated));

        if (isset($old['status']) && $old['status'] !== $new['status']) {
            AuditLogger::log('reservation.status_changed', $reservation, 'Estado da requisição alterado', $old, $new);
        } else {
            AuditLogger::log('reservation.updated', $reservation, 'Requisição atualizada', $old, $new);
        }

        return redirect()->route('reservations.index')->with('success', 'Requisição atualizada!');
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);
        $snapshot = $reservation->toArray();
        $reservation->delete();
        AuditLogger::log('reservation.deleted', $reservation, 'Requisição removida', $snapshot, null);

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
        AuditLogger::log('reservation.checkout', $reservation, 'Equipamento requisitado (checkout)');

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
        AuditLogger::log('reservation.checkin', $reservation, 'Equipamento devolvido (checkin)');

        return redirect()->route('reservations.show', $reservation)->with('success', 'Equipamento devolvido com sucesso!');
    }
}
