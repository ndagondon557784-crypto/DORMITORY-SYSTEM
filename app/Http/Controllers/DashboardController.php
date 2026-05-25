<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Announcement;
use App\Models\Building;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'    => Student::where('status', 'active')->count(),
            'total_rooms'       => Room::count(),
            'available_rooms'   => Room::where('status', 'available')->count(),
            'active_allocations' => Allocation::where('status', 'active')->count(),
            'total_revenue'     => Payment::where('status', 'paid')->sum('amount'),
            'pending_payments'  => Payment::where('status', 'pending')->count(),
            'occupancy_rate'    => $this->getOccupancyRate(),
            'total_buildings'   => Building::where('is_active', true)->count(),
        ];

        $recentAllocations = Allocation::with(['student', 'room.building'])
            ->latest()
            ->limit(5)
            ->get();

        $recentPayments = Payment::with('student')
            ->latest()
            ->limit(5)
            ->get();

        $announcements = Announcement::published()
            ->with('author')
            ->latest()
            ->limit(5)
            ->get();

        $monthlyRevenue = $this->getMonthlyRevenue();
        $buildingOccupancy = $this->getBuildingOccupancy();
        $roomTypeDistribution = $this->getRoomTypeDistribution();

        if (auth()->user()->isStudent()) {
            return $this->studentDashboard($stats, $announcements);
        }

        return view('dashboard.index', compact(
            'stats', 'recentAllocations', 'recentPayments',
            'announcements', 'monthlyRevenue', 'buildingOccupancy', 'roomTypeDistribution'
        ));
    }

    private function studentDashboard(array $stats, $announcements)
    {
        $student = auth()->user()->student;
        $activeAllocation = $student?->activeAllocation?->load(['room.building']);
        $payments = $student?->payments()->latest()->limit(5)->get() ?? collect();

        return view('dashboard.student', compact('stats', 'announcements', 'student', 'activeAllocation', 'payments'));
    }

    private function getOccupancyRate(): float
    {
        $total = Room::sum('capacity');
        $occupied = Room::sum('current_occupancy');
        return $total > 0 ? round(($occupied / $total) * 100, 1) : 0;
    }

    private function getMonthlyRevenue(): array
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'month'   => $date->format('M Y'),
                'revenue' => Payment::where('status', 'paid')
                    ->whereYear('payment_date', $date->year)
                    ->whereMonth('payment_date', $date->month)
                    ->sum('amount'),
            ];
        }
        return $months;
    }

    private function getBuildingOccupancy(): array
    {
        return Building::with('rooms')
            ->where('is_active', true)
            ->get()
            ->map(fn($b) => [
                'name'       => $b->name,
                'capacity'   => $b->total_capacity,
                'occupied'   => $b->current_occupancy,
            ])
            ->toArray();
    }

    private function getRoomTypeDistribution(): array
    {
        return Room::selectRaw('room_type, count(*) as count')
            ->groupBy('room_type')
            ->pluck('count', 'room_type')
            ->toArray();
    }
}