@extends('layouts.guest')

@section('title', 'Registar')

@section('content')
<div class="split-screen" data-page="register">
    <!-- Lado Esquerdo - Info -->
    <div class="split-left-register">
        <div class="info-content">
            <div class="logo-main">
                <i class="bi bi-box-seam"></i>
                <h1>LabStock</h1>
            </div>
            <h2>Novo por aqui?</h2>
            <p>Junte-se ao sistema de gestão de inventário mais eficiente.</p>

            <!-- Botão Mobile Toggle -->
            <button type="button" class="btn-mobile-toggle" id="mobileToggleBtn" style="display: none;">
                <i class="bi bi-person-plus-fill me-2"></i>
                CRIAR CONTA
            </button>

            <div class="features">
                <div class="feature-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Acesso rápido</span>
                </div>
                <div class="feature-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Gestão simples</span>
                </div>
                <div class="feature-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Histórico completo</span>
                </div>
            </div>
        </div>
        <div class="decorative-circle circle-1"></div>
        <div class="decorative-circle circle-2"></div>
        <div class="decorative-circle circle-3"></div>
    </div>

    <!-- Lado Direito - Formulário -->
    <div class="split-right-register-form">
        <div class="form-wrapper">
            <h2 class="form-title">Criar Conta</h2>
            <p class="form-subtitle">Preencha os seus dados</p>

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

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <div class="input-group-custom">
                    <i class="bi bi-person-fill input-icon"></i>
                    <input type="text" class="form-input" id="name" name="name"
                           value="{{ old('name') }}" placeholder="Nome Completo" required autofocus>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-envelope-fill input-icon"></i>
                    <input type="email" class="form-input" id="email" name="email"
                           value="{{ old('email') }}" placeholder="Email" required>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-telephone-fill input-icon"></i>
                    <input type="text" class="form-input" id="phone" name="phone"
                           value="{{ old('phone') }}" placeholder="Telefone (Opcional)">
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-building input-icon"></i>
                    <input type="text" class="form-input" id="department" name="department"
                           value="{{ old('department') }}" placeholder="Departamento (Opcional)">
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" class="form-input" id="password" name="password"
                           placeholder="Palavra-passe" required>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-shield-lock-fill input-icon"></i>
                    <input type="password" class="form-input" id="password_confirmation" name="password_confirmation"
                           placeholder="Confirmar Palavra-passe" required>
                </div>

                <button type="submit" class="btn-submit">
                    REGISTAR
                </button>

                <div class="form-footer">
                    <p>Já tem conta? <a href="{{ route('login') }}" class="link-auth">Entrar</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .register-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .split-screen {
        display: flex;
        min-height: 100vh;
        background: #fff;
        position: relative;
        overflow: hidden;
    }

    /* Círculo grande que substitui o background do lado esquerdo */
    .split-screen::before {
        content: '';
        position: absolute;
        width: 150%;
        height: 150%;
        background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
        border-radius: 50%;
        top: 50%;
        left: -75%;
        transform: translateY(-50%);
        z-index: 1;
        transition: 1.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        pointer-events: none;
    }

    /* ===================== LADO ESQUERDO - INFO ===================== */
    .split-left-register {
        flex: 1;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 3rem;
        z-index: 2;
    }

    .split-left-register .info-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
        animation: fadeIn 0.8s ease-out 0.3s both;
    }

    .split-left-register .logo-main {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2rem;
        animation: pulse 2s ease-in-out infinite;
    }

    .split-left-register .logo-main i {
        font-size: 3.5rem;
    }

    .split-left-register .logo-main h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }

    .split-left-register h2 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .split-left-register p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .split-left-register .features {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }

    .split-left-register .feature-badge {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .split-left-register .feature-badge:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(5px);
    }

    .split-left-register .feature-badge i {
        font-size: 1.2rem;
        color: #2ecc71;
    }

    /* Círculos decorativos */
    .decorative-circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        pointer-events: none;
    }

    .circle-1 {
        width: 300px;
        height: 300px;
        top: -100px;
        right: -100px;
        animation: float 6s ease-in-out infinite;
    }

    .circle-2 {
        width: 200px;
        height: 200px;
        bottom: -50px;
        left: -50px;
        animation: float 8s ease-in-out infinite reverse;
    }

    .circle-3 {
        width: 150px;
        height: 150px;
        top: 50%;
        left: 10%;
        animation: float 7s ease-in-out infinite;
    }

    /* ===================== LADO DIREITO - FORMULÁRIO ===================== */
    .split-right-register-form {
        flex: 1;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 2rem;
        position: relative;
        z-index: 2;
        clip-path: ellipse(100% 100% at 100% 50%);
    }

    .form-wrapper {
        width: 100%;
        max-width: 480px;
        animation: slideInLeft 0.6s ease-out;
    }

    .form-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .form-subtitle {
        color: #7f8c8d;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    .auth-form {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .input-group-custom {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #7f8c8d;
        font-size: 1.1rem;
        z-index: 1;
        pointer-events: none;
    }

    .form-input {
        width: 100%;
        padding: 1rem 1.25rem 1rem 3.25rem;
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        outline: none;
    }

    .form-input:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .form-input::placeholder {
        color: #bdc3c7;
    }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(52, 152, 219, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .form-footer {
        text-align: center;
        margin-top: 1.5rem;
    }

    .form-footer p {
        color: #7f8c8d;
        font-size: 0.95rem;
    }

    .link-auth {
        color: #3498db;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .link-auth:hover {
        color: #2c3e50;
        text-decoration: underline;
    }

    /* Alertas */
    .alert {
        border-radius: 15px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        border: none;
    }

    .alert ul {
        padding-left: 1.25rem;
        margin-bottom: 0;
    }

    .alert-danger {
        background: #fee;
        color: #c33;
    }

    /* ===================== ANIMAÇÕES ===================== */
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(5deg);
        }
    }

    /* Botão Mobile Toggle */
    .btn-mobile-toggle {
        width: 100%;
        max-width: 300px;
        padding: 1rem 2rem;
        background: white;
        color: #3498db;
        border: 2px solid white;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 2rem auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-mobile-toggle:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-mobile-toggle i {
        font-size: 1.2rem;
    }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 992px) {
        .split-screen {
            flex-direction: column;
        }

        .split-screen::before {
            display: none;
        }

        .btn-mobile-toggle {
            display: flex !important;
        }

        .split-right-register-form {
            clip-path: none;
            order: 2;
            padding: 2rem 1.5rem;
            display: none;
        }

        .split-right-register-form.active {
            display: flex;
        }

        .split-left-register {
            order: 1;
            padding: 2rem 1.5rem;
            min-height: 60vh;
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
        }

        .split-left-register .logo-main h1 {
            font-size: 2rem;
        }

        .split-left-register .logo-main i {
            font-size: 2.5rem;
        }

        .split-left-register h2 {
            font-size: 1.5rem;
        }

        .split-left-register p {
            font-size: 1rem;
        }

        .form-title {
            font-size: 1.75rem;
        }

        .circle-1,
        .circle-2,
        .circle-3 {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .form-wrapper {
            max-width: 100%;
        }

        .split-left-register,
        .split-right-register-form {
            padding: 1.5rem 1rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('mobileToggleBtn');
    const formSection = document.querySelector('.split-right-register-form');

    if (toggleBtn && formSection) {
        toggleBtn.addEventListener('click', function() {
            formSection.classList.toggle('active');

            if (formSection.classList.contains('active')) {
                formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }
});
</script>
@endsection
