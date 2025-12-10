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
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Departamento</th>
                        <th>Telefone</th>
                        <th>Role</th>
                        <th>Membro desde</th>
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
                        <td><small>{{ $user->created_at->format('d/m/Y') }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Nenhum utilizador encontrado</td>
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
