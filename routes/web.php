<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;

Route::get('/', fn() => redirect('/login'));

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected
Route::middleware('auth')->group(function () {
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
});