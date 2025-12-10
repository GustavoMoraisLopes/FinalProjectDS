<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

// Rotas públicas
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rotas protegidas (requerem autenticação)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Inventário
    Route::resource('equipments', EquipmentController::class);

    // Reservas
    Route::resource('reservations', ReservationController::class);
    Route::post('/reservations/{reservation}/checkout', [ReservationController::class, 'checkout'])->name('reservations.checkout');
    Route::post('/reservations/{reservation}/checkin', [ReservationController::class, 'checkin'])->name('reservations.checkin');

    // Scanner
    Route::get('/scanner', [ScannerController::class, 'index'])->name('scanner');
    Route::post('/scanner/search', [ScannerController::class, 'search'])->name('scanner.search');

    // Admin & Logs (apenas admin)
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.logs');
    });
});
