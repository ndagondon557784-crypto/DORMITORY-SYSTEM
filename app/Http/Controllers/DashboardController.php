<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Announcement;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Core stats ───────────────────────────────────────────────────────
        $stats = [
            'active_students'    => Student::where('status', 'active')->count(),
            'available_rooms'    => Room::where('status', 'available')->count(),
            'active_allocations' => Allocation::where('status', 'active')->count(),
            'pending_payments'   => Payment::whereIn('status', ['pending', 'overdue'])->count(),
        ];

        // ── Occupancy rate (uses current_occupancy column from your schema) ──
        $totalBeds     = (int) Room::sum('capacity');
        $occupiedBeds  = (int) Room::sum('current_occupancy');
        $occupancyRate = $totalBeds > 0
            ? round(($occupiedBeds / $totalBeds) * 100)
            : 0;

        // ── Room list with safe fallback for current_occupancy ───────────────
        $rooms = Room::with('building')->get()->map(function ($room) {
            $room->current_occupancy = $room->current_occupancy ?? 0;
            return $room;
        });

        // ── Recent activity ──────────────────────────────────────────────────
        $recentAllocations = Allocation::with(['student', 'room.building'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['student', 'allocation.room'])
            ->latest()
            ->take(5)
            ->get();

        $overduePayments = Payment::with('student')
            ->where('status', 'overdue')
            ->latest()
            ->get();

        $maintenanceRooms = Room::with('building')
            ->where('status', 'maintenance')
            ->get();

        $announcements = Announcement::with('author')
            ->where('is_published', true)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'occupancyRate',
            'rooms',
            'recentAllocations',
            'recentPayments',
            'overduePayments',
            'maintenanceRooms',
            'announcements'
        ));
    }
}
