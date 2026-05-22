<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\{
    DashboardController,
    DormitoryController,
    StudentController,
    RoomController,
    AllocationController,
    PaymentController,
    UserController,
    NotificationController,
};
use Illuminate\Support\Facades\Route;

// Public landing page
Route::get('/', fn() => view('welcome'))->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Dormitories
    Route::resource('dormitories', DormitoryController::class);

    // Rooms
    Route::resource('rooms', RoomController::class);
    Route::get('/rooms-availability', [RoomController::class, 'checkAvailability'])->name('rooms.availability');

    // Students
    Route::resource('students', StudentController::class);

    // Allocations
    Route::resource('allocations', AllocationController::class);
    Route::post('/allocations/{allocation}/checkout', [AllocationController::class, 'checkout'])->name('allocations.checkout');

    // Payments
    Route::resource('payments', PaymentController::class);
    Route::get('/students/{student}/allocation', [PaymentController::class, 'getStudentAllocations'])->name('students.allocation');

    // Users
    Route::resource('users', UserController::class);

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
        Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
    });
});