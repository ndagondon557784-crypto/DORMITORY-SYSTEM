<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Room;
use App\Models\Allocation;
use App\Models\Payment;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'active_students'    => 0,
            'available_rooms'    => 0,
            'active_allocations' => 0,
            'pending_payments'   => 0,
        ];

        $occupancyRate     = 0;
        $rooms             = collect();
        $recentAllocations = collect();
        $recentPayments    = collect();
        $overduePayments   = collect();
        $maintenanceRooms  = collect();
        $announcements     = collect();

        try {
            $stats['active_students'] = Student::where('status', 'Active')->count();
        } catch (\Exception $e) {}

        try {
            $stats['available_rooms'] = Room::where('status', 'Available')->count();
        } catch (\Exception $e) {}

        try {
            $stats['active_allocations'] = Allocation::where('status', 'Active')->count();
        } catch (\Exception $e) {}

        try {
            $stats['pending_payments'] = Payment::whereIn('status', ['Pending', 'Overdue'])->count();
        } catch (\Exception $e) {}

        try {
            if (Schema::hasTable('rooms')) {
                $totalBeds     = Room::sum('capacity') ?? 0;
                $occupiedBeds  = Schema::hasColumn('rooms', 'occupied') ? (Room::sum('occupied') ?? 0) : 0;
                $occupancyRate = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100) : 0;
                $rooms = Room::all()->map(function ($room) {
                    $room->occupied = $room->occupied ?? 0;
                    return $room;
                });
            }
        } catch (\Exception $e) {}

        try {
            if (Schema::hasTable('allocations')) {
                $recentAllocations = Allocation::with(['student', 'room'])->latest()->take(5)->get();
            }
        } catch (\Exception $e) {}

        try {
            if (Schema::hasTable('payments')) {
                $recentPayments  = Payment::with(['student', 'room'])->latest()->take(5)->get();
                $overduePayments = Payment::with('student')->where('status', 'Overdue')->get();
            }
        } catch (\Exception $e) {}

        try {
            if (Schema::hasTable('rooms')) {
                $maintenanceRooms = Room::where('status', 'Maintenance')->get();
            }
        } catch (\Exception $e) {}

        try {
            if (Schema::hasTable('announcements')) {
                $announcements = \App\Models\Announcement::with('author')->latest()->take(5)->get();
            }
        } catch (\Exception $e) {}

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