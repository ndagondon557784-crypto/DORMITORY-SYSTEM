<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function occupancy()
    {
        $rooms = Room::with(['building', 'activeAllocations.student'])->get();
        return view('reports.occupancy', compact('rooms'));
    }

    public function revenue(Request $request)
    {
        $year = $request->year ?? now()->year;

        $monthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthly[] = [
                'month'  => date('F', mktime(0, 0, 0, $m, 1)),
                'amount' => Payment::where('status', 'paid')
                    ->whereYear('payment_date', $year)
                    ->whereMonth('payment_date', $m)
                    ->sum('amount'),
            ];
        }

        $total = array_sum(array_column($monthly, 'amount'));

        return view('reports.revenue', compact('monthly', 'total', 'year'));
    }

    public function allocations(Request $request)
    {
        $allocations = Allocation::with(['student', 'room.building'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('reports.allocations', compact('allocations'));
    }
}