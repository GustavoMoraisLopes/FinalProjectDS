@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="login-wrapper">
    <div class="login-container">
        <!-- Info Panel -->
        <div class="login-side-panel">
            <div class="panel-content">
                <div class="logo-section">
                    <i class="bi bi-box-seam"></i>
                    <h2>LabStock</h2>
                </div>
                <h3>Bem-vindo ao Sistema de Gestão de Inventário</h3>
                <p>Gerencie equipamentos, requisições e utilizadores de forma eficiente e centralizada.</p>
                <div class="features-list">
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Gestão de equipamentos</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Requisições simplificadas</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Auditoria completa</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="login-form-panel">
            <div class="form-content">
                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-2">Iniciar Sessão</h4>
                    <p class="text-muted">Introduza as suas credenciais</p>
                </div>

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

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="nome@exemplo.com" required autofocus>
                        <label for="email"><i class="bi bi-envelope me-2"></i>Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Manter sessão iniciada
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login">
                        <span>Entrar</span>
                        <i class="bi bi-arrow-right-circle ms-2"></i>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        Não tem conta? <a href="{{ route('register') }}" class="link-primary fw-semibold">Criar conta</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        padding: 2rem 1rem;
        position: relative;
        overflow: hidden;
    }

    .login-wrapper::before {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 30px 30px;
        animation: backgroundMove 20s linear infinite;
    }

    @keyframes backgroundMove {
        0% { transform: translate(0, 0); }
        100% { transform: translate(30px, 30px); }
    }

    .login-container {
        display: flex;
        max-width: 1100px;
        width: 100%;
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
        animation: slideUp 0.5s ease-out;
        position: relative;
        z-index: 1;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-side-panel {
        flex: 1;
        background: linear-gradient(135deg, #6f9db4 0%, #5a8399 100%);
        padding: 3rem;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .login-side-panel::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .panel-content {
        position: relative;
        z-index: 1;
    }

    .logo-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .logo-section i {
        font-size: 3rem;
        animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .logo-section h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .login-side-panel h3 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .login-side-panel p {
        font-size: 1rem;
        opacity: 0.95;
        margin-bottom: 2rem;
    }

    .features-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        opacity: 0.95;
    }

    .feature-item i {
        font-size: 1.2rem;
        color: #9edbb3;
    }

    .login-form-panel {
        flex: 1;
        padding: 3rem;
        display: flex;
        align-items: center;
    }

    .form-content {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    .login-form .form-floating > .form-control {
        border-radius: 12px;
        border: 2px solid #e9ecef;
        padding: 1rem 1rem 1rem 3rem;
        height: 58px;
        transition: all 0.3s ease;
    }

    .login-form .form-floating > .form-control:focus {
        border-color: #6f9db4;
        box-shadow: 0 0 0 0.25rem rgba(111, 157, 180, 0.15);
    }

    .login-form .form-floating > label {
        padding-left: 3rem;
        color: #6c757d;
    }

    .btn-login {
        height: 56px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.05rem;
        background: linear-gradient(135deg, #6f9db4 0%, #5a8399 100%);
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(111, 157, 180, 0.3);
        background: linear-gradient(135deg, #5a8399 0%, #6f9db4 100%);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .form-check-input:checked {
        background-color: #6f9db4;
        border-color: #6f9db4;
    }

    .link-primary {
        color: #6f9db4 !important;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .link-primary:hover {
        color: #5a8399 !important;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .login-side-panel {
            display: none;
        }

        .login-form-panel {
            padding: 2rem 1.5rem;
        }

        .login-container {
            border-radius: 16px;
        }
    }
</style>
@endsection
