@extends('layouts.guest')

@section('title', 'Recuperar Palavra-passe')

@section('content')
<div class="split-screen" data-page="reset">
    <div class="split-left-register">
        <div class="info-content">
            <div class="logo-main">
                <i class="bi bi-box-seam"></i>
                <h1>LabStock</h1>
            </div>
            <h2>Redefinir palavra-passe</h2>
            <p>Defina uma nova palavra-passe segura para voltar a aceder ao sistema.</p>
            <div class="features">
                <div class="feature-badge">
                    <i class="bi bi-shield-lock"></i>
                    <span>Segurança reforçada</span>
                </div>
                <div class="feature-badge">
                    <i class="bi bi-lightning-charge"></i>
                    <span>Processo rápido</span>
                </div>
            </div>
        </div>
        <div class="decorative-circle circle-1"></div>
        <div class="decorative-circle circle-2"></div>
        <div class="decorative-circle circle-3"></div>
    </div>

    <div class="split-right-register-form">
        <div class="form-wrapper">
            <h2 class="form-title">Nova palavra-passe</h2>
            <p class="form-subtitle">Introduza a nova palavra-passe para continuar.</p>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group-custom">
                    <i class="bi bi-envelope-fill input-icon"></i>
                    <input type="email" class="form-input" id="email" name="email"
                           value="{{ old('email', $email) }}" placeholder="Email" required autofocus>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" class="form-input" id="password" name="password"
                           placeholder="Nova palavra-passe" required>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-shield-lock-fill input-icon"></i>
                    <input type="password" class="form-input" id="password_confirmation" name="password_confirmation"
                           placeholder="Confirmar palavra-passe" required>
                </div>

                <button type="submit" class="btn-submit">
                    ATUALIZAR PALAVRA-PASSE
                </button>

                <div class="form-footer">
                    <p>Voltar ao login? <a href="{{ route('login') }}" class="link-auth">Iniciar sessão</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
