<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LabStock') - Sistema de Gestão de Inventário</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Flatpickr CSS (Datepicker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Tema adaptado às cores do site */
        .flatpickr-calendar { border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
        .flatpickr-months .flatpickr-month { color: #1f2937; }
        .flatpickr-weekdays { background: #f8fafc; }
        .flatpickr-day.today { border-color: #667eea; }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.inRange:hover { background: #667eea; border-color: #667eea; color: #fff; }
        .flatpickr-day:hover { background: #e5edff; }
        .flatpickr-monthDropdown-months, .numInputWrapper input { color: #111827; }
        .flatpickr-day.disabled { color: #9ca3af; }
    </style>

    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @auth
        <aside class="sidebar bg-dark-blue" id="sidebar">
            <div class="sidebar-header p-3">
                <div class="d-flex align-items-center">
                    <div class="logo-icon me-2">
                        <i class="bi bi-box-seam text-white fs-4"></i>
                    </div>
                    <h4 class="text-white mb-0 fw-bold">LabStock</h4>
                </div>
            </div>

            <nav class="sidebar-nav mt-4">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('equipments.index') }}" class="sidebar-link {{ request()->routeIs('equipments.*') ? 'active' : '' }}">
                    <i class="bi bi-laptop"></i>
                    <span>Inventário</span>
                </a>

                <a href="{{ route('reservations.index') }}" class="sidebar-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>Reservas</span>
                </a>

                <a href="{{ route('scanner') }}" class="sidebar-link {{ request()->routeIs('scanner') ? 'active' : '' }}">
                    <i class="bi bi-upc-scan"></i>
                    <span>Scanner</span>
                </a>

                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.index') }}" class="sidebar-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock"></i>
                    <span>Admin & Logs</span>
                </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <a href="{{ route('profile.show') }}" class="user-profile-link" style="text-decoration: none;">
                    <div class="user-profile p-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-2">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                         alt="Avatar" class="rounded-circle"
                                         style="width: 40px; height: 40px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);">
                                @else
                                    <i class="bi bi-person-circle text-white fs-3"></i>
                                @endif
                            </div>
                            <div class="user-info">
                                <div class="text-white fw-bold">{{ auth()->user()->name }}</div>
                                <small class="text-white-50">{{ auth()->user()->role }}</small>
                            </div>
                        </div>
                    </div>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-2 px-3">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none">
                        <i class="bi bi-box-arrow-left"></i> Sair
                    </button>
                </form>
            </div>
        </aside>
        @endauth

        <!-- Main Content -->
        <div class="flex-fill main-content">
            @auth
            <header class="top-bar bg-white border-bottom p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-link text-dark d-md-none" id="toggleSidebar">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <div>
                        <h5 class="mb-0">@yield('page-title', 'LabStock')</h5>
                        <small class="text-muted">@yield('page-subtitle', '')</small>
                    </div>
                    <div>
                        <span class="text-muted">Bem-vindo, {{ auth()->user()->name }}</span>
                    </div>
                </div>
            </header>
            @endauth

            <main class="container-fluid px-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

    <script>
        // Inicializar datepickers globais
        document.addEventListener('DOMContentLoaded', function() {
            if (window.flatpickr) {
                flatpickr('.datepicker', {
                    allowInput: true,
                    dateFormat: 'd/m/Y',
                    locale: flatpickr.l10ns.pt,
                    minDate: 'today'
                });
                flatpickr('.daterange', {
                    mode: 'range',
                    allowInput: true,
                    dateFormat: 'd/m/Y',
                    locale: flatpickr.l10ns.pt,
                    minDate: 'today'
                });
            }
        });
    </script>

    <script>
        // Toggle sidebar em mobile
        document.getElementById('toggleSidebar')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>

    @stack('scripts')
</body>
</html>
