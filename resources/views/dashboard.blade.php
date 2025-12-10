@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Bem-vindo ao LabStock, ' . auth()->user()->name . '.')

@section('content')
<div class="row g-3 mb-4">
    <!-- Total Inventário -->
    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Inventário</p>
                        <h3 class="fw-bold mb-0">{{ $totalEquipments }}</h3>
                        <small class="text-muted">Equipamentos registados</small>
                    </div>
                    <div class="stat-icon blue">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disponíveis -->
    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Disponíveis</p>
                        <h3 class="fw-bold mb-0">{{ $availableEquipments }}</h3>
                        <small class="text-muted">Prontos a requisitar</small>
                    </div>
                    <div class="stat-icon green">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emprestados -->
    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Emprestados</p>
                        <h3 class="fw-bold mb-0">{{ $loanedEquipments }}</h3>
                        <small class="text-muted">Equipamentos fora</small>
                    </div>
                    <div class="stat-icon orange">
                        <i class="bi bi-arrow-right-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Manutenção -->
    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Manutenção</p>
                        <h3 class="fw-bold mb-0">{{ $maintenanceEquipments }}</h3>
                        <small class="text-muted">Indisponíveis</small>
                    </div>
                    <div class="stat-icon red">
                        <i class="bi bi-tools"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Reservas Recentes -->
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Reservas Recentes</h5>
            </div>
            <div class="card-body">
                @if($recentReservations->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentReservations as $reservation)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $reservation->equipment->name }}</h6>
                                    <small class="text-muted">
                                        {{ $reservation->start_date->format('d/m/Y') }} - {{ $reservation->end_date->format('d/m/Y') }}
                                    </small>
                                </div>
                                <span class="badge status-{{ $reservation->status }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">Nenhuma reserva encontrada</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Acesso Rápido -->
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Acesso Rápido</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('equipments.index') }}" class="quick-action-btn">
                        <div class="quick-action-icon blue">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div class="quick-action-text">
                            <div class="quick-action-title">Ver Inventário</div>
                            <div class="quick-action-desc">Gerir equipamentos</div>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>

                    <a href="{{ route('reservations.create') }}" class="quick-action-btn">
                        <div class="quick-action-icon green">
                            <i class="bi bi-plus-circle"></i>
                        </div>
                        <div class="quick-action-text">
                            <div class="quick-action-title">Nova Reserva</div>
                            <div class="quick-action-desc">Requisitar equipamento</div>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>

                    <a href="{{ route('scanner') }}" class="quick-action-btn">
                        <div class="quick-action-icon cyan">
                            <i class="bi bi-upc-scan"></i>
                        </div>
                        <div class="quick-action-text">
                            <div class="quick-action-title">Scanner</div>
                            <div class="quick-action-desc">Pesquisar rapidamente</div>
                        </div>
                        <div class="quick-action-arrow">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                </div>

                <hr class="my-4">

                <div class="mt-3">
                    <div class="info-box mb-2">
                        <i class="bi bi-calendar-check text-primary"></i>
                        <span class="info-label">Minhas Requisições Ativas</span>
                        <span class="info-value">{{ $myActiveReservations }}</span>
                    </div>
                    <div class="info-box warning">
                        <i class="bi bi-clock-history text-warning"></i>
                        <span class="info-label">Aprovações Pendentes</span>
                        <span class="info-value">{{ $pendingApprovals }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Quick Action Buttons */
    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        color: inherit;
    }

    .quick-action-btn:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .quick-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .quick-action-icon.blue {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .quick-action-icon.green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .quick-action-icon.cyan {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
    }

    .quick-action-text {
        flex: 1;
    }

    .quick-action-title {
        font-weight: 600;
        font-size: 1rem;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .quick-action-desc {
        font-size: 0.85rem;
        color: #64748b;
    }

    .quick-action-arrow {
        font-size: 1.2rem;
        color: #cbd5e1;
        transition: all 0.3s ease;
    }

    .quick-action-btn:hover .quick-action-arrow {
        color: #667eea;
        transform: translateX(3px);
    }

    /* Info Boxes */
    .info-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 3px solid #3b82f6;
    }

    .info-box.warning {
        background: #fef3c7;
        border-left-color: #f59e0b;
    }

    .info-box i {
        font-size: 1.25rem;
    }

    .info-label {
        flex: 1;
        color: #64748b;
        font-size: 0.9rem;
    }

    .info-value {
        font-weight: 700;
        font-size: 1.1rem;
        color: #1e293b;
    }
</style>
@endsection
