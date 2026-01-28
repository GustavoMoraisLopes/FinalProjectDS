@extends('layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'Registro de Auditoria')
@section('page-subtitle', 'Histórico completo de ações no sistema.')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.logs') }}" class="row g-3 align-items-end logs-filters">
            <div class="col-lg-6 col-md-12">
                <label for="action" class="form-label">Ação</label>
                <input type="text" class="form-control" name="action" placeholder="Filtrar por ação..." value="{{ request('action') }}">
            </div>
            <div class="col-lg-4 col-md-12">
                <label for="user_id" class="form-label">Utilizador</label>
                <select name="user_id" class="form-select">
                    <option value="">Todos os utilizadores</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 btn-sm">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('admin.logs') }}" class="btn btn-outline-secondary w-100 btn-sm">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle mb-0 audit-table">
                <thead class="bg-light">
                    <tr>
                        <th class="w-15">Data/Hora</th>
                        <th class="w-15">Utilizador</th>
                        <th class="w-15">Ação</th>
                        <th class="w-15">Modelo</th>
                        <th class="w-40">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td data-label="Data/Hora"><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
                        <td data-label="Utilizador"><small>{{ $log->user?->name ?? 'Sistema' }}</small></td>
                        <td data-label="Ação">
                            <div class="action-cell">
                                <strong class="text-dark">
                                    @switch($log->action)
                                        @case('user_promoted_to_teacher')
                                            Utilizador promovido a professor
                                            @break
                                        @case('user_teacher_request_rejected')
                                            Pedido de professor rejeitado
                                            @break
                                        @case('reservation.created')
                                            Requisição criada
                                            @break
                                        @case('reservation.updated')
                                            Requisição atualizada
                                            @break
                                        @case('reservation.deleted')
                                            Requisição eliminada
                                            @break
                                        @case('reservation.checkin')
                                            Equipamento devolvido (check-in)
                                            @break
                                        @case('reservation.checkout')
                                            Equipamento levantado (check-out)
                                            @break
                                        @case('reservation.status_changed')
                                            Estado da requisição alterado
                                            @break
                                        @case('equipment.updated')
                                            Equipamento atualizado
                                            @break
                                        @case('equipment.created')
                                            Equipamento criado
                                            @break
                                        @default
                                            {{ str_replace('_', ' ', ucfirst($log->action)) }}
                                    @endswitch
                                </strong>
                            </div>
                        </td>
                        <td data-label="Modelo"><small class="text-truncate d-inline-block" style="max-width: 180px;">{{ $log->model_type }}</small></td>
                        <td data-label="Descrição"><small class="text-truncate d-inline-block" style="max-width: 360px;" title="{{ $log->description }}">{{ $log->description }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Nenhum registro encontrado</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $logs->links('pagination::bootstrap-4') }}
</div>

@push('styles')
<style>
    .audit-table {
        font-size: 1rem;
    }
    .audit-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f8fafc;
        font-size: 0.95rem;
        font-weight: 600;
    }
    .audit-table th,
    .audit-table td {
        vertical-align: middle;
        white-space: nowrap;
    }
    .audit-table td:nth-child(3) {
        white-space: normal;
    }
    .audit-table td:nth-child(5) {
        white-space: normal;
    }
    .action-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .action-cell strong {
        font-weight: 600;
        font-size: 0.95rem;
    }
    @media (max-width: 768px) {
        .logs-filters {
            flex-direction: column;
        }
        .audit-table thead {
            display: none;
        }
        .audit-table, .audit-table tbody,
        .audit-table tr, .audit-table td {
            display: block;
            width: 100%;
        }
        .audit-table tr {
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem;
            background: #fff;
        }
        .audit-table td {
            text-align: left !important;
            padding: 0.5rem 0;
            border: none;
            white-space: normal !important;
        }
        .audit-table td::before {
            content: attr(data-label);
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }
        .audit-table td small {
            max-width: 100% !important;
        }
        .audit-table .action-cell {
            display: block;
        }
        .audit-table .action-cell strong {
            display: block;
            margin-top: 0.25rem;
        }
    }
</style>
@endpush
@endsection
