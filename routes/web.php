<?php

use App\Http\Controllers\AllocationController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', fn() => redirect()->route('dashboard'));

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Student-accessible
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');

    // Staff + Admin only
    Route::middleware('role:admin,staff')->group(function () {

        // Students
        Route::resource('students', StudentController::class);

        // Rooms
        Route::resource('rooms', RoomController::class);
        Route::get('/api/rooms/available', [RoomController::class, 'available'])->name('rooms.available');

        // Allocations
        Route::resource('allocations', AllocationController::class)->except(['edit', 'update', 'destroy']);
        Route::post('/allocations/{allocation}/checkout', [AllocationController::class, 'checkOut'])->name('allocations.checkout');

        // Payments
        Route::resource('payments', PaymentController::class)->except(['edit', 'update', 'destroy']);

        // Announcements management
        Route::resource('announcements', AnnouncementController::class)->except(['index', 'show']);

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/occupancy', [ReportController::class, 'occupancy'])->name('occupancy');
            Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
            Route::get('/allocations', [ReportController::class, 'allocations'])->name('allocations');
        });
    });

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});