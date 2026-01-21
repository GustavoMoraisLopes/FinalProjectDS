<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Equipment;
use App\Models\ReservationItem;
use Illuminate\Http\Request;
use App\Services\AuditLogger;
use Carbon\Carbon;
use App\Notifications\ReservationApprovedNotification;
use App\Notifications\ReservationRejectedNotification;
use App\Notifications\ReturnReminderNotification;

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
        $equipments = Equipment::with('equipmentType', 'category')
            ->where('status', 'available')
            ->get();

        return view('reservations.create', compact('equipments', 'equipment_id'));
    }

    public function store(Request $request)
    {
        $rules = [
            'equipment_id' => 'required|exists:equipments,id',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
            'pickup_time' => 'required|date_format:H:i',
            'return_time' => 'required|date_format:H:i',
            'purpose' => 'nullable|string|max:255',
            'project' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'accessories' => 'nullable|array', // Acessórios selecionados
            'accessories.*.equipment_id' => 'exists:equipments,id',
            'accessories.*.quantity' => 'numeric|min:1',
        ];

        // Validações diferentes para aluno vs professor
        if (auth()->user()->isStudent()) {
            // Aluno: dados académicos vêm do perfil (inputs hidden)
            $rules['school'] = 'required|in:istec,ipta,outro';
            $rules['course_type'] = 'required|string|max:100';
            $rules['course_name'] = 'required|string|max:255';
            $rules['class_year'] = 'required|string|max:100';
        } else {
            // Professor: dados académicos devem ser selecionados manualmente
            $rules['school'] = 'required|in:istec,ipta,outro';
            $rules['course_type'] = 'required_unless:school,outro|string|max:100';
            $rules['course_name'] = 'required_unless:school,outro|string|max:255';
            $rules['class_year'] = 'required_unless:school,outro|string|max:100';
        }

        $validated = $request->validate($rules);

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

        // Adicionar equipamento principal como item
        ReservationItem::create([
            'reservation_id' => $reservation->id,
            'equipment_id' => $validated['equipment_id'],
            'quantity' => 1,
            'item_type' => 'main',
        ]);

        // Adicionar acessórios se existirem
        if (isset($validated['accessories']) && is_array($validated['accessories'])) {
            foreach ($validated['accessories'] as $accessory) {
                if (isset($accessory['equipment_id']) && $accessory['equipment_id']) {
                    ReservationItem::create([
                        'reservation_id' => $reservation->id,
                        'equipment_id' => $accessory['equipment_id'],
                        'quantity' => $accessory['quantity'] ?? 1,
                        'item_type' => 'accessory',
                    ]);
                }
            }
        }

        AuditLogger::log(
            'reservation.created',
            $reservation,
            'Requisição criada com acessórios',
            null,
            [
                'equipment_id' => $reservation->equipment_id,
                'start_date' => $reservation->start_date,
                'end_date' => $reservation->end_date,
                'pickup_time' => $reservation->pickup_time,
                'return_time' => $reservation->return_time,
                'status' => $reservation->status,
                'total_items' => $reservation->items()->count(),
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

            // Send notification on status change
            if ($new['status'] === 'approved') {
                $reservation->user->notify(new ReservationApprovedNotification($reservation));
                // Marcar equipamento como emprestado imediatamente quando aprovado
                $reservation->equipment->update(['status' => 'loaned']);
                $reservation->update(['checked_out_at' => now()]);
                AuditLogger::log('reservation.checkout', $reservation, 'Equipamento requisitado automaticamente ao aprovar');
                $this->sendReturnReminderIfClose($reservation);
            } elseif ($new['status'] === 'cancelled') {
                $reason = $request->input('rejection_reason');
                $reservation->user->notify(new ReservationRejectedNotification($reservation, $reason));
            }
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

        // Disparar lembrete imediato se devolução é hoje/amanhã ou já em atraso
        $this->sendReturnReminderIfClose($reservation);

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

    /**
     * Envia lembrete imediato se a devolução é hoje/amanhã ou já está em atraso.
     */
    private function sendReturnReminderIfClose(Reservation $reservation): void
    {
        $today = Carbon::today();
        $endDate = Carbon::parse($reservation->end_date);
        $daysLeft = $today->diffInDays($endDate, false); // negativo se atrasado

        if ($daysLeft <= 1) {
            // Evitar duplicar no mesmo dia
            $alreadyNotified = $reservation->user->notifications()
                ->where('type', ReturnReminderNotification::class)
                ->where('data->reservation_id', $reservation->id)
                ->whereDate('created_at', $today)
                ->exists();

            if (!$alreadyNotified) {
                $reservation->user->notify(new ReturnReminderNotification($reservation, max(0, $daysLeft)));
            }
        }
    }
}
