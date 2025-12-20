@extends('layouts.app')

@section('title', $equipment->name)
@section('page-title', $equipment->name)
@section('page-subtitle', $equipment->category->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detalhes do Equipamento</h5>
                @if(auth()->user()->isAdmin())
                <div class="d-flex gap-2">
                    <a href="{{ route('equipments.edit', $equipment) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <form action="{{ route('equipments.destroy', $equipment) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja remover este equipamento?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Número de Série</h6>
                        <p><code>{{ $equipment->serial_number }}</code></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Categoria</h6>
                        <p>{{ $equipment->category->name }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Estado</h6>
                        <p>
                            @if($equipment->status == 'available')
                                <span class="badge status-available">Disponível</span>
                            @elseif($equipment->status == 'loaned')
                                <span class="badge status-loaned">Emprestado</span>
                            @elseif($equipment->status == 'maintenance')
                                <span class="badge status-maintenance">Manutenção</span>
                            @else
                                <span class="badge bg-secondary">Indisponível</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Condição</h6>
                        <p>{{ $equipment->condition ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Localização</h6>
                        <p>{{ $equipment->location ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Data de Compra</h6>
                        <p>{{ $equipment->purchase_date ? $equipment->purchase_date->format('d/m/Y') : '-' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Preço de Compra</h6>
                        <p>{{ $equipment->purchase_price ? '€ ' . number_format($equipment->purchase_price, 2, ',', '.') : '-' }}</p>
                    </div>
                </div>

                @if($equipment->description)
                <div class="mb-4">
                    <h6 class="text-muted">Descrição</h6>
                    <p>{{ $equipment->description }}</p>
                </div>
                @endif

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('equipments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                    @if($equipment->status == 'available')
                    <a href="{{ route('reservations.create') }}?equipment_id={{ $equipment->id }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Requisitar
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Histórico de Reservas</h5>
            </div>
            <div class="card-body">
                @if($equipment->reservations->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($equipment->reservations->take(5) as $reservation)
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-muted">{{ $reservation->user->name }}</small>
                                    <p class="mb-1 small">{{ $reservation->start_date->format('d/m/Y') }} a {{ $reservation->end_date->format('d/m/Y') }}</p>
                                </div>
                                <span class="badge status-{{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3 small">Nenhuma reserva registada</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
