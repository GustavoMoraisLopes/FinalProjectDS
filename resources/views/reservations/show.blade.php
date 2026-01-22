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
                        <p><a href="{{ route('equipments.show', $reservation->equipment) }}" style="text-decoration: none;">{{ $reservation->equipment->name }}</a></p>
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

                @if($reservation->pickup_time || $reservation->return_time)
                <div class="row mb-4">
                    @if($reservation->pickup_time)
                    <div class="col-md-6">
                        <h6 class="text-muted">Hora de Levantamento</h6>
                        <p><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($reservation->pickup_time)->format('H:i') }}</p>
                    </div>
                    @endif
                    @if($reservation->return_time)
                    <div class="col-md-6">
                        <h6 class="text-muted">Hora de Devolução</h6>
                        <p><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($reservation->return_time)->format('H:i') }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <hr class="my-4">

                <h6 class="text-muted mb-3"><i class="bi bi-book"></i> Contexto Académico</h6>
                @if($reservation->school || $reservation->course_type || $reservation->course_name || $reservation->class_year)
                <div class="row mb-4">
                    @if($reservation->school)
                    <div class="col-md-6">
                        <h6 class="text-muted">Instituição</h6>
                        <p>
                            @if($reservation->school === 'istec')
                                ISTEC Porto
                            @elseif($reservation->school === 'ipta')
                                IPTA Porto
                            @else
                                {{ ucfirst($reservation->school) }}
                            @endif
                        </p>
                    </div>
                    @endif
                    @if($reservation->course_type)
                    <div class="col-md-6">
                        <h6 class="text-muted">Tipo de Curso</h6>
                        <p>{{ $reservation->course_type }}</p>
                    </div>
                    @endif
                </div>
                <div class="row mb-4">
                    @if($reservation->course_name)
                    <div class="col-md-6">
                        <h6 class="text-muted">Curso</h6>
                        <p>{{ $reservation->course_name }}</p>
                    </div>
                    @endif
                    @if($reservation->class_year)
                    <div class="col-md-6">
                        <h6 class="text-muted">Turma / Ano</h6>
                        <p>{{ $reservation->class_year }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <hr class="my-4">

                @if($reservation->purpose)
                <div class="mb-4">
                    <h6 class="text-muted"><i class="bi bi-bookmark"></i> Finalidade / Descrição</h6>
                    <p>{{ $reservation->purpose }}</p>
                </div>
                @endif

                @if($reservation->project)
                <div class="mb-4">
                    <h6 class="text-muted"><i class="bi bi-folder"></i> Projeto</h6>
                    <p>{{ $reservation->project }}</p>
                </div>
                @endif

                @if($reservation->notes)
                <div class="mb-4">
                    <h6 class="text-muted"><i class="bi bi-chat-left-text"></i> Observações</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $reservation->notes }}
                    </div>
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

                <hr class="my-4">
            </div>
        </div>

        <!-- Card de Equipamentos Requisitados -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-box-seam"></i> Equipamentos Requisitados</h5>
            </div>
            <div class="card-body">
                @foreach($reservation->items as $item)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="text-muted mb-2">{{ $item->equipment->name }}</h6>
                        <small class="text-muted d-block">
                            <i class="bi bi-bookmark"></i> {{ $item->equipment->category->name }}
                            <span class="mx-1">•</span>
                            <i class="bi bi-hash"></i> {{ $item->equipment->serial_number }}
                            @if($item->quantity > 1)
                            <span class="mx-1">•</span>
                            Qtd: {{ $item->quantity }}
                            @endif
                        </small>
                    </div>
                </div>
                @if(!$loop->last)
                <hr class="my-3">
                @endif
                @endforeach
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
@endsection
