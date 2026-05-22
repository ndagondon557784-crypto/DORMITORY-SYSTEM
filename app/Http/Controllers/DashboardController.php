<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Allocation, ActivityLog, Dormitory, Payment, Room, Student, User};
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::where('status', 'active')->count(),
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'active_allocations' => Allocation::where('status', 'active')->count(),
            'total_revenue' => Payment::where('status', 'paid')
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'overdue_payments' => Payment::where('status', 'overdue')->count(),
            'total_dormitories' => Dormitory::where('is_active', true)->count(),
        ];

        // Monthly revenue chart data (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => Payment::where('status', 'paid')
                    ->whereYear('payment_date', $month->year)
                    ->whereMonth('payment_date', $month->month)
                    ->sum('amount'),
            ];
        }

        // Room type distribution
        $roomTypes = Room::selectRaw('room_type, count(*) as count')
            ->groupBy('room_type')
            ->pluck('count', 'room_type')
            ->toArray();

        // Recent activity
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Recent allocations
        $recentAllocations = Allocation::with(['student', 'room.dormitory'])
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        // Upcoming check-outs (within 30 days)
        $upcomingCheckouts = Allocation::with(['student', 'room'])
            ->where('status', 'active')
            ->whereNotNull('expected_check_out_date')
            ->where('expected_check_out_date', '<=', now()->addDays(30))
            ->orderBy('expected_check_out_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'monthlyRevenue', 'roomTypes',
            'recentActivities', 'recentAllocations', 'upcomingCheckouts'
        ));
    }

    public function analytics()
    {
        return view('admin.analytics');
    }
}