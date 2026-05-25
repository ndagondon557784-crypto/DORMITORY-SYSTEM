<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AnnouncementController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('allocations', AllocationController::class);
    Route::post('/allocations/{id}/vacate', [AllocationController::class, 'vacate'])->name('allocations.vacate');
    Route::post('/allocations/{id}/transfer', [AllocationController::class, 'transfer'])->name('allocations.transfer');

    Route::resource('payments', PaymentController::class);
    Route::post('/payments/{id}/mark-paid', [PaymentController::class, 'markPaid'])->name('payments.markPaid');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');

    Route::resource('announcements', AnnouncementController::class);
});