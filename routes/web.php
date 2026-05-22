<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\AllocationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Student\PortalController;
use App\Http\Controllers\Student\ApplicationController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// ═══════════════════════════════════════════════════════════════
// PUBLIC ROUTES — No login required
// ═══════════════════════════════════════════════════════════════
Route::get('/', fn () => view('welcome'))->name('home');

// ═══════════════════════════════════════════════════════════════
// GUEST-ONLY ROUTES — Redirect to dashboard if already logged in
// ═══════════════════════════════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login',    [AuthenticatedSessionController::class, 'store']);
    Route::get('/register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Logout — must be POST, must be authenticated
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ═══════════════════════════════════════════════════════════════
// AUTHENTICATED ROUTES — Must be logged in
// ═══════════════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {

    /*
     * SMART DASHBOARD
     *
     * This single route handles both admin and student.
     * DashboardController::index() checks auth()->user()->isAdmin()
     * and returns either admin.dashboard or student.dashboard view.
     *
     * Admin  → view('admin.dashboard')
     * Student → view('student.dashboard')
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ═══════════════════════════════════════════════════════════
    // ADMIN ROUTES — Protected by AdminMiddleware (role = admin)
    //
    // Any user with role = 'student' who tries to visit
    // /admin/* will get a 403 error from AdminMiddleware.
    // ═══════════════════════════════════════════════════════════
    Route::middleware('admin')
         ->prefix('admin')
         ->name('admin.')
         ->group(function () {

        // Room management
        Route::resource('rooms', RoomController::class);

        // Student management
        Route::resource('students', StudentController::class);

        // Allocation management
        Route::resource('allocations', AllocationController::class);

        // Admin account management
        Route::resource('admins', AdminController::class);

        // Application management (approve/reject)
        Route::get('applications',
            [AdminApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{application}',
            [AdminApplicationController::class, 'show'])->name('applications.show');
        Route::post('applications/{application}/approve',
            [AdminApplicationController::class, 'approve'])->name('applications.approve');
        Route::post('applications/{application}/reject',
            [AdminApplicationController::class, 'reject'])->name('applications.reject');

        // Analytics
        Route::get('reports',       [DashboardController::class, 'reports'])->name('reports');
        Route::get('activity-logs', [DashboardController::class, 'activityLogs'])->name('activity-logs');
    });

    // ═══════════════════════════════════════════════════════════
    // STUDENT ROUTES — Accessible by any authenticated user
    //
    // NOTE: We do NOT apply StudentMiddleware here because admins
    // may need to preview student pages. If you want strict
    // student-only access, add ->middleware('student') below.
    // ═══════════════════════════════════════════════════════════
    Route::prefix('student')
         ->name('student.')
         ->group(function () {

        // Profile and room view
        Route::get('portal', [PortalController::class, 'index'])->name('portal');
        Route::get('room',   [PortalController::class, 'room'])->name('room');

        // Room application workflow
        Route::get('apply',  [ApplicationController::class, 'create'])->name('apply');
        Route::post('apply', [ApplicationController::class, 'store'])->name('apply.store');
        Route::post('apply/{application}/cancel',
            [ApplicationController::class, 'cancel'])->name('apply.cancel');
        Route::get('application-status',
            [ApplicationController::class, 'status'])->name('application.status');

        // Edit profile
        Route::get('profile/edit',   [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    });
});