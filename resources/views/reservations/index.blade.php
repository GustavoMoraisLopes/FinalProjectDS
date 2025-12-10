@extends('layouts.app')

@section('title', 'Reservas')
@section('page-title', 'Reservas')
@section('page-subtitle', 'Geri requisições de equipamento.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <form method="GET" action="{{ route('reservations.index') }}" class="d-flex gap-2">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Todas as Requisições</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovadas</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completadas</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Canceladas</option>
        </select>
    </form>

    <a href="{{ route('reservations.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-circle"></i> Nova Requisição
    </a>
</div>

<div class="row g-3">
    @forelse($reservations as $reservation)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">{{ $reservation->equipment->name }}</h6>
                    <small class="text-muted">{{ $reservation->equipment->category->name }}</small>
                </div>
                @if($reservation->status == 'pending')
                    <span class="badge bg-warning">Pendente</span>
                @elseif($reservation->status == 'approved')
                    <span class="badge bg-success">Aprovada</span>
                @elseif($reservation->status == 'completed')
                    <span class="badge bg-dark">Completa</span>
                @else
                    <span class="badge bg-danger">Cancelada</span>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Requisitante</small>
                    <p class="mb-0">{{ $reservation->user->name }}</p>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Período</small>
                    <p class="mb-0">
                        <i class="bi bi-calendar"></i> {{ $reservation->start_date->format('d/m/Y') }} — {{ $reservation->end_date->format('d/m/Y') }}
                    </p>
                </div>

                @if($reservation->purpose)
                <div class="mb-3">
                    <small class="text-muted">Finalidade</small>
                    <p class="mb-0 small">{{ $reservation->purpose }}</p>
                </div>
                @endif

                <div class="d-flex gap-2 mt-3 pt-3 border-top">
                    <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                    @if(auth()->user()->isAdmin() && $reservation->status == 'pending')
                    <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="d-inline" style="flex: 1;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-sm btn-success w-100">
                            <i class="bi bi-check"></i> Aprovar
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="text-muted mt-2">Nenhuma requisição encontrada</p>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $reservations->links() }}
</div>
@endsection
