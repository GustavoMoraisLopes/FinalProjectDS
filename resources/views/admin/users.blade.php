@extends('layouts.app')

@section('title', 'Utilizadores')
@section('page-title', 'Gestão de Utilizadores')
@section('page-subtitle', 'Listar e gerir utilizadores do sistema.')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Utilizadores Registados</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle mb-0 users-table">
                <thead>
                    <tr>
                        <th class="w-25">Nome</th>
                        <th class="w-25">Email</th>
                        <th class="w-15">Departamento</th>
                        <th class="w-10 text-center">Telefone</th>
                        <th class="w-8 text-center">Role</th>
                        <th class="w-10 text-center">Tipo</th>
                        <th class="w-10 text-center">Membro desde</th>
                        <th class="w-12 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->department ?? '-' }}</td>
                        <td class="text-center">{{ $user->phone ?? '-' }}</td>
                        <td class="text-center">
                            @if($user->isAdmin())
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="badge-row">
                                @if($user->isTeacher())
                                    <span class="badge bg-success">Professor</span>
                                @else
                                    <span class="badge bg-info">Aluno</span>
                                @endif
                                @if($user->hasPendingTeacherRequest())
                                    <span class="badge bg-warning text-dark d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-clock"></i>
                                        Pedido Pendente
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="text-center"><small>{{ $user->created_at->format('d/m/Y') }}</small></td>
                        <td class="text-center actions-cell">
                            @if($user->hasPendingTeacherRequest())
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <form action="{{ route('admin.approve-teacher', $user->id) }}" method="POST" onsubmit="return confirm('Aprovar {{ $user->name }} como professor?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Aprovar como Professor">
                                            <i class="bi bi-check-circle"></i> Aprovar
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reject-teacher', $user->id) }}" method="POST" onsubmit="return confirm('Rejeitar o pedido de {{ $user->name }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Rejeitar pedido">
                                            <i class="bi bi-x-circle"></i> Rejeitar
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Nenhum utilizador encontrado</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>
@endsection

@push('styles')
<style>
    .users-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f8fafc;
    }
    .users-table th,
    .users-table td {
        vertical-align: middle;
        white-space: nowrap;
    }
    .users-table td:nth-child(1),
    .users-table td:nth-child(2) {
        white-space: normal;
    }
    .badge-row {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
        justify-content: center;
    }
    .actions-cell .btn {
        min-width: 96px;
        white-space: nowrap;
    }
    @media (max-width: 992px) {
        .users-table {
            font-size: 0.9rem;
        }
    }
</style>
@endpush
