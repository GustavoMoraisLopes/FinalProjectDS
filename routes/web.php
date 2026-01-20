<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;

// Rotas públicas
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Forgot Password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Rotas protegidas (requerem autenticação)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    // Removida rota /profile/edit (não utilizada)
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/request-teacher', [ProfileController::class, 'requestTeacherAccess'])->name('profile.request-teacher');

    // Lembretes
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::post('/reminders/{id}/mark-read', [ReminderController::class, 'markAsRead'])->name('reminders.mark-read');
    Route::post('/reminders/mark-all-read', [ReminderController::class, 'markAllAsRead'])->name('reminders.mark-all-read');
    Route::delete('/reminders/{id}', [ReminderController::class, 'delete'])->name('reminders.delete');
    Route::post('/reminders/delete-selected', [ReminderController::class, 'deleteSelected'])->name('reminders.delete-selected');
    Route::post('/reminders/delete-all', [ReminderController::class, 'deleteAll'])->name('reminders.delete-all');

    // Inventário
    Route::resource('equipments', EquipmentController::class);

    // Reservas
    Route::resource('reservations', ReservationController::class);
    Route::post('/reservations/{reservation}/checkout', [ReservationController::class, 'checkout'])->name('reservations.checkout');
    Route::post('/reservations/{reservation}/checkin', [ReservationController::class, 'checkin'])->name('reservations.checkin');

    // API para Acessórios (Cascata)
    Route::get('/api/accessories/{equipmentId}', 'App\Http\Controllers\Api\AccessoriesController@getAccessories')->name('api.accessories');
    Route::get('/api/check-availability/{equipmentId}', 'App\Http\Controllers\Api\AccessoriesController@checkAvailability')->name('api.check-availability');

    // Scanner
    Route::get('/scanner', [ScannerController::class, 'index'])->name('scanner');
    Route::post('/scanner/search', [ScannerController::class, 'search'])->name('scanner.search');

    // Admin & Logs (apenas admin)
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users/{id}/approve-teacher', [AdminController::class, 'approveTeacher'])->name('admin.approve-teacher');
        Route::post('/admin/users/{id}/reject-teacher', [AdminController::class, 'rejectTeacher'])->name('admin.reject-teacher');
        Route::get('/admin/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.logs');
    });
});
