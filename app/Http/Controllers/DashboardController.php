<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Room;
use App\Models\Allocation;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index() {
        $stats = [
            'active_students'    => Student::where('status', 'Active')->count(),
            'available_rooms'    => Room::where('status', 'Available')->count(),
            'active_allocations' => Allocation::where('status', 'Active')->count(),
            'pending_payments'   => Payment::whereIn('status', ['Pending', 'Overdue'])->count(),
        ];

        $totalBeds     = Room::sum('capacity');
        $occupiedBeds  = Room::sum('occupied');
        $occupancyRate = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100) : 0;

        $rooms              = Room::all();
        $recentAllocations  = Allocation::with(['student', 'room'])->latest()->take(5)->get();
        $recentPayments     = Payment::with(['student', 'room'])->latest()->take(5)->get();
        $overduePayments    = Payment::with('student')->where('status', 'Overdue')->get();
        $maintenanceRooms   = Room::where('status', 'Maintenance')->get();

        return view('dashboard', compact(
            'stats', 'occupancyRate', 'rooms',
            'recentAllocations', 'recentPayments',
            'overduePayments', 'maintenanceRooms'
        ));
    }
}