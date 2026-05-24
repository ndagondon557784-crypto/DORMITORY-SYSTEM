<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Room;
use App\Models\Allocation;
use App\Models\Payment;
use App\Models\Announcement;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $student = $user->student;
            $currentAllocation = $student->currentAllocation;
            $recentPayments = $student->payments()->latest()->limit(5)->get();
            $totalPaid = $student->payments()->where('status', 'completed')->sum('amount');
            $outstandingBalance = $student->outstanding_balance;

            return view('dashboard.student', [
                'student' => $student,
                'currentAllocation' => $currentAllocation,
                'recentPayments' => $recentPayments,
                'totalPaid' => $totalPaid,
                'outstandingBalance' => $outstandingBalance,
            ]);
        }

        $totalStudents = Student::count();
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $activeAllocations = Allocation::where('status', 'active')->count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $recentPayments = Payment::latest()->limit(10)->get();
        $announcements = Announcement::where('is_pinned', true)
            ->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->get();

        return view('dashboard.admin', [
            'totalStudents' => $totalStudents,
            'totalRooms' => $totalRooms,
            'occupiedRooms' => $occupiedRooms,
            'activeAllocations' => $activeAllocations,
            'totalRevenue' => $totalRevenue,
            'recentPayments' => $recentPayments,
            'announcements' => $announcements,
        ]);
    }
}