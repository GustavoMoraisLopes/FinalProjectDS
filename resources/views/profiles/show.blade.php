@extends('layouts.app')

@section('title', 'Meu Perfil')
@section('page-title', 'Meu Perfil')
@section('page-subtitle', 'Gerenciar informações da conta e preferências')

@section('content')
<div class="row">
    <!-- Card de Perfil -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center py-5">
                <div class="mb-3 position-relative">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-profile-img rounded-circle">
                    @else
                        <div class="avatar-profile">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    @endif
                </div>
                <h4 class="card-title mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">
                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                </p>
                <div class="text-start">
                    <small class="d-block mb-2">
                        <i class="bi bi-envelope"></i> <strong>Email:</strong> {{ $user->email }}
                    </small>
                    @if($user->phone)
                    <small class="d-block mb-2">
                        <i class="bi bi-telephone"></i> <strong>Telefone:</strong> {{ $user->phone }}
                    </small>
                    @endif
                    @if($user->department)
                    <small class="d-block mb-2">
                        <i class="bi bi-building"></i> <strong>Departamento:</strong> {{ $user->department }}
                    </small>
                    @endif
                    <small class="d-block">
                        <i class="bi bi-calendar"></i> <strong>Membro desde:</strong> {{ $user->created_at->format('d/m/Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulários de Edição -->
    <div class="col-lg-8">
        <!-- Editar Perfil -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square"></i> Editar Perfil
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Upload de Avatar -->
                    <div class="mb-4">
                        <label class="form-label">Foto de Perfil</label>
                        <div class="text-center">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar Preview"
                                     class="avatar-preview rounded-circle mb-3" id="avatarPreview">
                            @else
                                <div class="avatar-preview-placeholder rounded-circle mb-3 mx-auto" id="avatarPreviewPlaceholder">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <img src="" alt="Avatar Preview" class="avatar-preview rounded-circle mb-3 d-none" id="avatarPreview">
                            @endif
                        </div>
                        <div class="d-flex gap-2 justify-content-center">
                            <label for="avatar" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-upload"></i> Escolher Foto
                            </label>
                            <input type="file" class="d-none @error('avatar') is-invalid @enderror"
                                   id="avatar" name="avatar" accept="image/*" onchange="previewAvatar(event)">
                            @if($user->avatar)
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAvatar()">
                                    <i class="bi bi-trash"></i> Remover
                                </button>
                            @endif
                        </div>
                        <small class="text-muted d-block mt-2">JPG, PNG ou GIF (máx. 2MB)</small>
                        @error('avatar')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="mb-3">

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+351 xxx xxx xxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="department" class="form-label">Departamento</label>
                        <input type="text" class="form-control @error('department') is-invalid @enderror"
                               id="department" name="department" value="{{ old('department', $user->department) }}" placeholder="ex: TI, RH, Financeiro">
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Guardar Alterações
                    </button>
                </form>
            </div>
        </div>

        <!-- Alterar Palavra-passe -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-lock"></i> Alterar Palavra-passe
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Palavra-passe Atual</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Palavra-passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Palavra-passe</label>
                        <input type="password" class="form-control" id="password_confirmation"
                               name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-arrow-repeat"></i> Alterar Palavra-passe
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-profile {
        font-size: 6rem;
        color: #667eea;
    }

    .avatar-profile-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 4px solid #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 3px solid #667eea;
    }

    .avatar-preview-placeholder {
        width: 150px;
        height: 150px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #cbd5e1;
    }

    .avatar-preview-placeholder i {
        font-size: 4rem;
        color: #94a3b8;
    }
</style>

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPreviewPlaceholder');

                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    }

    function removeAvatar() {
        if (confirm('Tem a certeza que deseja remover a foto de perfil?')) {
            // Criar um input hidden para indicar remoção
            const form = document.querySelector('form[action="{{ route('profile.update') }}"]');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_avatar';
            input.value = '1';
            form.appendChild(input);
            form.submit();
        }
    }
</script>
@endsection
