<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Room;
use App\Models\Allocation;
use App\Models\Application;
use App\Models\User;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    /**
     * ROLE-BASED DASHBOARD DISPATCHER
     *
     * This is the single entry point for /dashboard.
     * It checks the authenticated user's role and returns
     * the correct view with the correct data.
     *
     * Admin  → private adminDashboard()  → view('admin.dashboard')
     * Student → private studentDashboard() → view('student.dashboard')
     *
     * HOW ROLE DETECTION WORKS:
     * auth()->user() returns the currently logged-in User model.
     * isAdmin() checks if $this->role === 'admin' (from the database).
     * isStudent() checks if $this->role === 'student'.
     */
    public function index()
    {
        // If the logged-in user has role = 'admin', show admin dashboard
        if (auth()->user()->isAdmin()) {
            return $this->adminDashboard();
        }

        // Otherwise (role = 'student'), show student dashboard
        return $this->studentDashboard();
    }

    // ── ADMIN DASHBOARD ───────────────────────────────────────────

    private function adminDashboard()
    {
        // Collect all statistics for admin view
        $stats = [
            'total_students'       => Student::count(),
            'total_rooms'          => Room::count(),
            'active_allocations'   => Allocation::where('status', 'active')->count(),
            'available_rooms'      => Room::where('status', 'available')->count(),
            'full_rooms'           => Room::where('status', 'full')->count(),
            'maintenance_rooms'    => Room::where('status', 'maintenance')->count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'unassigned_students'  => Student::whereDoesntHave('allocations',
                fn ($q) => $q->where('status', 'active')
            )->count(),
        ];

        $recentAllocations   = Allocation::with(['student.user', 'room'])->latest()->take(5)->get();
        $pendingApplications = Application::with(['student.user', 'room'])
            ->where('status', 'pending')->latest()->take(5)->get();
        $rooms               = Room::withCount(['activeAllocations as occupied'])->orderBy('room_number')->get();
        $recentLogs          = ActivityLog::with('user')->latest()->take(8)->get();

        // Returns: resources/views/admin/dashboard.blade.php
        return view('admin.dashboard', compact(
            'stats', 'recentAllocations', 'pendingApplications', 'rooms', 'recentLogs'
        ));
    }

    // ── STUDENT DASHBOARD ─────────────────────────────────────────

    private function studentDashboard()
    {
        $student     = auth()->user()->student;
        $allocation  = $student?->activeAllocation()->with('room')->first();
        $application = $student?->latestApplication()->with('room')->first();

        // Returns: resources/views/student/dashboard.blade.php
        return view('student.dashboard', compact('student', 'allocation', 'application'));
    }

    // ── ADMIN REPORTS ─────────────────────────────────────────────

    public function reports()
    {
        $rooms = Room::withCount(['activeAllocations as occupied'])
            ->orderBy('building')->orderBy('room_number')->get();

        $genderStats = [
            'male'   => Student::where('gender', 'male')->count(),
            'female' => Student::where('gender', 'female')->count(),
            'other'  => Student::where('gender', 'other')->count(),
        ];

        $courseStats = Student::selectRaw('course, COUNT(*) as total')
            ->groupBy('course')->orderByDesc('total')->get();

        $yearStats = Student::selectRaw('year_level, COUNT(*) as total')
            ->groupBy('year_level')->orderBy('year_level')->get();

        $applicationStats = [
            'pending'  => Application::where('status', 'pending')->count(),
            'approved' => Application::where('status', 'approved')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        return view('admin.reports', compact(
            'rooms', 'genderStats', 'courseStats', 'yearStats', 'applicationStats'
        ));
    }

    // ── ACTIVITY LOGS ─────────────────────────────────────────────

    public function activityLogs()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(20);
        return view('admin.activity-logs', compact('logs'));
    }
}