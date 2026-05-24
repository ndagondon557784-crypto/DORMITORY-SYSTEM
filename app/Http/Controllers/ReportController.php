<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function occupancy(Request $request)
    {
        $rooms = Room::withCount(['activeAllocations'])
            ->with(['activeAllocations.student'])
            ->get();

        $stats = [
            'total_rooms' => $rooms->count(),
            'occupied' => $rooms->where('status', 'occupied')->count(),
            'available' => $rooms->where('status', 'available')->count(),
            'maintenance' => $rooms->where('status', 'maintenance')->count(),
            'overall_rate' => $rooms->count() > 0
                ? round(($rooms->where('status', 'occupied')->count() / $rooms->count()) * 100, 1)
                : 0,
        ];

        return view('reports.occupancy', compact('rooms', 'stats'));
    }

    public function payments(Request $request)
    {
        $year = $request->get('year', now()->year);

        $monthlyData = Payment::where('status', 'verified')
            ->whereYear('payment_date', $year)
            ->select(DB::raw('MONTH(payment_date) as month'), DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $totalRevenue = Payment::where('status', 'verified')->whereYear('payment_date', $year)->sum('total_amount');
        $pendingAmount = Payment::where('status', 'pending')->sum('total_amount');

        $recentPayments = Payment::with(['student', 'allocation.room'])
            ->where('status', 'verified')
            ->whereYear('payment_date', $year)
            ->latest()->take(20)->get();

        return view('reports.payments', compact('monthlyData', 'totalRevenue', 'pendingAmount', 'recentPayments', 'year'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'students');

        if ($type === 'students') {
            $data = Student::with(['activeAllocation.room'])->get();
            return view('reports.export.students', compact('data'));
        } elseif ($type === 'payments') {
            $data = Payment::with(['student', 'allocation.room'])->where('status', 'verified')->get();
            return view('reports.export.payments', compact('data'));
        } elseif ($type === 'rooms') {
            $data = Room::withCount(['activeAllocations'])->get();
            return view('reports.export.rooms', compact('data'));
        }

        return back()->with('error', 'Invalid export type.');
    }
}