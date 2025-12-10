@extends('layouts.app')

@section('title', 'Administração')
@section('page-title', 'Administração')
@section('page-subtitle', 'Logs de auditoria e gestão de utilizadores.')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total de Utilizadores</p>
                        <h3 class="fw-bold mb-0">{{ $totalUsers }}</h3>
                    </div>
                    <div class="stat-icon blue">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Administradores</p>
                        <h3 class="fw-bold mb-0">{{ $adminUsers }}</h3>
                    </div>
                    <div class="stat-icon orange">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Atividade Recente</h5>
                <a href="{{ route('admin.logs') }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                    <i class="bi bi-box-arrow-up-right"></i> Ver Todos
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Data/Hora</th>
                                <th>Utilizador</th>
                                <th>Ação</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                            <tr>
                                <td><small>{{ $log->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>{{ $log->user?->name ?? 'Sistema' }}</td>
                                <td><code>{{ $log->action }}</code></td>
                                <td><small>{{ substr($log->description, 0, 50) }}...</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Sem atividade registada</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Atalhos</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.users') }}" class="shortcut-btn">
                        <div class="d-flex align-items-center gap-3">
                            <span class="shortcut-icon bg-primary-subtle text-primary"><i class="bi bi-people"></i></span>
                            <div>
                                <div class="fw-semibold">Gerir Utilizadores</div>
                                <small class="text-muted">Criar, editar e desativar contas</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-primary"></i>
                    </a>
                    <a href="{{ route('admin.logs') }}" class="shortcut-btn">
                        <div class="d-flex align-items-center gap-3">
                            <span class="shortcut-icon bg-info-subtle text-info"><i class="bi bi-file-text"></i></span>
                            <div>
                                <div class="fw-semibold">Ver Todos os Logs</div>
                                <small class="text-muted">Auditorias e eventos recentes</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-info"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
<style>
    .shortcut-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.9rem 1rem;
        border: 1px solid #dbe7f0;
        border-radius: 12px;
        background: linear-gradient(135deg, #f8fafc 0%, #f3f8fb 100%);
        color: #1f2a30;
        text-decoration: none;
        box-shadow: 0 6px 18px rgba(31, 42, 48, 0.06);
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease, background 0.15s ease;
    }

    .shortcut-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(31, 42, 48, 0.12);
        border-color: #6f9db4;
        background: linear-gradient(135deg, #e6f1f7 0%, #f8fbfd 100%);
    }

    .shortcut-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        font-size: 1.1rem;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
    }

    .stat-card .stat-icon.blue {
        background: rgba(111, 157, 180, 0.12);
        color: #6f9db4;
    }

    .stat-card .stat-icon.orange {
        background: rgba(201, 106, 75, 0.12);
        color: #c96a4b;
    }

    .card-header .btn-outline-primary {
        border-color: #6f9db4;
        color: #6f9db4;
    }

    .card-header .btn-outline-primary:hover {
        background: #6f9db4;
        color: #fff;
    }
</style>
@endpush
@endsection
