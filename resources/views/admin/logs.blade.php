@extends('layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'Registro de Auditoria')
@section('page-subtitle', 'Histórico completo de ações no sistema.')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.logs') }}" class="row g-2">
            <div class="col-md-6">
                <label for="action" class="form-label">Ação</label>
                <input type="text" class="form-control" name="action" placeholder="Filtrar por ação..." value="{{ request('action') }}">
            </div>
            <div class="col-md-6">
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
            <div class="col-12 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('admin.logs') }}" class="btn btn-secondary">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Data/Hora</th>
                        <th>Utilizador</th>
                        <th>Ação</th>
                        <th>Modelo</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
                        <td>{{ $log->user?->name ?? 'Sistema' }}</td>
                        <td><code>{{ $log->action }}</code></td>
                        <td><small>{{ $log->model_type }}</small></td>
                        <td><small>{{ substr($log->description, 0, 60) }}{{ strlen($log->description) > 60 ? '...' : '' }}</small></td>
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
    {{ $logs->links() }}
</div>
@endsection
