@extends('layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'Registro de Auditoria')
@section('page-subtitle', 'Histórico completo de ações no sistema.')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.logs') }}" class="row g-3 align-items-end">
            <div class="col-lg-6">
                <label for="action" class="form-label">Ação</label>
                <input type="text" class="form-control" name="action" placeholder="Filtrar por ação..." value="{{ request('action') }}">
            </div>
            <div class="col-lg-4">
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
            <div class="col-lg-2 d-flex gap-2">
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
                        <td><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
                        <td><small>{{ $log->user?->name ?? 'Sistema' }}</small></td>
                        <td>
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
                        <td><small class="text-truncate d-inline-block" style="max-width: 180px;">{{ $log->model_type }}</small></td>
                        <td><small class="text-truncate d-inline-block" style="max-width: 360px;" title="{{ $log->description }}">{{ $log->description }}</small></td>
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
    .audit-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f8fafc;
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
    }
    @media (max-width: 992px) {
        .audit-table {
            font-size: 0.9rem;
        }
    }
</style>
@endpush
@endsection
