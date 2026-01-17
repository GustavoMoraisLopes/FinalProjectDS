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
            <table class="table table-hover mb-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 20%;">Nome</th>
                        <th style="width: 25%;">Email</th>
                        <th style="width: 15%;">Departamento</th>
                        <th style="width: 12%;">Telefone</th>
                        <th style="width: 8%;">Role</th>
                        <th style="width: 12%;">Tipo</th>
                        <th style="width: 12%;">Membro desde</th>
                        <th style="width: 10%;" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->department ?? '-' }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            @if($user->isAdmin())
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                        </td>
                        <td>
                            @if($user->isTeacher())
                                <span class="badge bg-success">Professor</span>
                            @else
                                <span class="badge bg-info">Aluno</span>
                            @endif
                            @if($user->hasPendingTeacherRequest())
                                <br><span class="badge bg-warning mt-1"><i class="bi bi-clock"></i> Pedido Pendente</span>
                            @endif
                        </td>
                        <td><small>{{ $user->created_at->format('d/m/Y') }}</small></td>
                        <td class="text-center">
                            @if($user->hasPendingTeacherRequest())
                                <form action="{{ route('admin.approve-teacher', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Aprovar {{ $user->name }} como professor?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Aprovar como Professor" style="white-space: nowrap;">
                                        <i class="bi bi-check-circle"></i> Aprovar
                                    </button>
                                </form>
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
