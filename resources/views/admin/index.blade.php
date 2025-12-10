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
                <a href="{{ route('admin.logs') }}" class="btn btn-sm btn-outline-secondary">Ver Todos</a>
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
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-people me-2"></i> Gerir Utilizadores
                    </a>
                    <a href="{{ route('admin.logs') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-file-text me-2"></i> Ver Todos os Logs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
