@extends('layouts.app')

@section('title', 'Requisição - ' . $reservation->equipment->name)
@section('page-title', 'Detalhes da Requisição')
@section('page-subtitle', $reservation->equipment->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Informações da Requisição</h5>
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
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Equipamento</h6>
                        <p><a href="{{ route('equipments.show', $reservation->equipment) }}">{{ $reservation->equipment->name }}</a></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Requisitante</h6>
                        <p>{{ $reservation->user->name }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Data de Início</h6>
                        <p>{{ $reservation->start_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Data de Fim</h6>
                        <p>{{ $reservation->end_date->format('d/m/Y') }}</p>
                    </div>
                </div>

                @if($reservation->purpose)
                <div class="mb-4">
                    <h6 class="text-muted">Finalidade</h6>
                    <p>{{ $reservation->purpose }}</p>
                </div>
                @endif

                @if($reservation->checked_out_at)
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Requisitado em</h6>
                        <p>{{ $reservation->checked_out_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($reservation->checked_in_at)
                    <div class="col-md-6">
                        <h6 class="text-muted">Devolvido em</h6>
                        <p>{{ $reservation->checked_in_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>

                    @if(auth()->user()->isAdmin())
                        @if($reservation->status == 'pending')
                        <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Aprovar
                            </button>
                        </form>
                        @endif

                        @if($reservation->status == 'approved' && !$reservation->checked_out_at)
                        <form action="{{ route('reservations.checkout', $reservation) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-box-arrow-right"></i> Requisitar
                            </button>
                        </form>
                        @endif

                        @if($reservation->checked_out_at && !$reservation->checked_in_at)
                        <form action="{{ route('reservations.checkin', $reservation) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-box-arrow-in"></i> Devolver
                            </button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
