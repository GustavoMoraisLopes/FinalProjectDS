<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LabStock - Sistema de Gestão de Inventário</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            bottom: -80px;
            left: -80px;
        }

        .welcome-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            padding: 20px;
            max-width: 950px;
        }

        .welcome-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .welcome-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem;
        }

        .welcome-image {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: #667eea;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        h1 {
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-weight: 700;
            font-size: 2.5rem;
        }

        .subtitle {
            color: #64748b;
            margin-bottom: 2rem;
            font-size: 1.05rem;
            font-weight: 500;
        }

        .description {
            color: #78849c;
            margin-bottom: 2rem;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-bottom: 2.5rem;
        }

        .btn-login, .btn-register {
            flex: 1;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-register {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
        }

        .btn-register:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
            text-decoration: none;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid #e8ecf1;
        }

        .feature-item {
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .feature-icon.icon-blue { color: #667eea; }
        .feature-icon.icon-purple { color: #764ba2; }
        .feature-icon.icon-pink { color: #f093fb; }
        .feature-icon.icon-cyan { color: #4facfe; }

        .feature-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .feature-desc {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.5;
        }

        .illustration {
            perspective: 1000px;
        }

        .illustration-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 3rem 2rem;
            color: white;
            text-align: center;
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
            transform: rotateY(-5deg) rotateX(5deg);
            transform-style: preserve-3d;
        }

        .illustration-card i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            display: block;
            opacity: 0.9;
        }

        .illustration-text {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .illustration-subtext {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .nav-top {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 20;
        }

        .nav-btn {
            padding: 8px 20px;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .welcome-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .welcome-card {
                padding: 2rem;
            }

            .welcome-image {
                display: none;
            }

            h1 {
                font-size: 2rem;
            }

            .button-group {
                flex-direction: column;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .nav-top {
                top: 10px;
                right: 10px;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="nav-top">
        @auth
            <a href="{{ url('/dashboard') }}" class="nav-btn">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="nav-btn">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </a>
        @endauth
    </div>

    <div class="welcome-wrapper">
        <div class="welcome-container">
            <!-- Left Side -->
            <div class="welcome-card">
                <div class="logo">
                    <i class="bi bi-box-seam"></i>
                </div>

                <h1>LabStock</h1>
                <p class="subtitle">Sistema de Gestão de Inventário Informático</p>

                <p class="description">
                    Gerencie equipamentos, controle reservas e acompanhe manutenções de forma simples, eficiente e intuitiva.
                </p>

                <div class="button-group">
                    <a href="{{ route('login') }}" class="btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Entrar
                    </a>
                    <a href="{{ route('register') }}" class="btn-register">
                        <i class="bi bi-person-plus me-2"></i> Cadastro
                    </a>
                </div>

                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon icon-blue">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div class="feature-title">Inventário</div>
                        <div class="feature-desc">Gerencie equipamentos</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon icon-purple">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="feature-title">Reservas</div>
                        <div class="feature-desc">Controle empréstimos</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon icon-pink">
                            <i class="bi bi-tools"></i>
                        </div>
                        <div class="feature-title">Manutenção</div>
                        <div class="feature-desc">Acompanhe reparos</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon icon-cyan">
                            <i class="bi bi-search"></i>
                        </div>
                        <div class="feature-title">Busca</div>
                        <div class="feature-desc">Localize equipamentos</div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Illustration -->
            <div class="welcome-image">
                <div class="illustration">
                    <div class="illustration-card">
                        <i class="bi bi-server"></i>
                        <div class="illustration-text">Sistema Completo</div>
                        <div class="illustration-subtext">Tudo em um único lugar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
