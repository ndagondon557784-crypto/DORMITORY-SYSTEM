<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Room;
use App\Models\Allocation;
use App\Models\Payment;

class ReportController extends Controller
{
    public function index() {
        $data = [
            'total_students'     => Student::count(),
            'active_students'    => Student::where('status', 'Active')->count(),
            'total_rooms'        => Room::count(),
            'occupancy_rate'     => $this->getOccupancyRate(),
            'total_collected'    => Payment::where('status', 'Paid')->sum('amount'),
            'total_pending'      => Payment::where('status', 'Pending')->sum('amount'),
            'total_overdue'      => Payment::where('status', 'Overdue')->sum('amount'),
            'collection_rate'    => $this->getCollectionRate(),
        ];

        $rooms          = Room::all();
        $courseBreakdown = Student::where('status', 'Active')
            ->selectRaw('course, count(*) as total')
            ->groupBy('course')->get();

        $monthlyPayments = Payment::selectRaw('month, year, sum(amount) as total, status')
            ->groupBy('month', 'year', 'status')
            ->orderBy('year')->orderBy('month')->get();

        return view('reports.index', compact('data', 'rooms', 'courseBreakdown', 'monthlyPayments'));
    }

    public function export(string $type) {
        // Hook into your PDF/Excel export package here (e.g. barryvdh/laravel-dompdf)
        return back()->with('info', "Export for {$type} — connect your PDF/Excel package here.");
    }

    private function getOccupancyRate(): int {
        $total    = Room::sum('capacity');
        $occupied = Room::sum('occupied');
        return $total > 0 ? round(($occupied / $total) * 100) : 0;
    }

    private function getCollectionRate(): int {
        $total = Payment::count();
        $paid  = Payment::where('status', 'Paid')->count();
        return $total > 0 ? round(($paid / $total) * 100) : 0;
    }
}