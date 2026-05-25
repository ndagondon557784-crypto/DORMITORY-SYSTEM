<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAllocationRequest;
use App\Models\Allocation;
use App\Models\Room;
use App\Models\Student;
use App\Services\AllocationService;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function __construct(private AllocationService $allocationService) {}

    public function index(Request $request)
    {
        $query = Allocation::with(['student', 'room.building', 'assignedBy'])
            ->when($request->search, fn($q, $s) => $q->whereHas('student', fn($q) => $q->where('full_name', 'like', "%{$s}%")))
            ->when($request->status, fn($q, $s) => $q->where('status', $s));

        $allocations = $query->latest()->paginate(15)->withQueryString();

        return view('allocations.index', compact('allocations'));
    }

    public function create()
    {
        $students = Student::where('status', 'active')
            ->whereDoesntHave('allocations', fn($q) => $q->where('status', 'active'))
            ->orderBy('full_name')
            ->get();

        $rooms = Room::with('building')->where('status', '!=', 'full')->where('status', '!=', 'maintenance')->get();

        return view('allocations.create', compact('students', 'rooms'));
    }

    public function store(StoreAllocationRequest $request)
    {
        $student = Student::findOrFail($request->student_id);
        $room    = Room::findOrFail($request->room_id);

        try {
            $allocation = $this->allocationService->allocate($student, $room, $request->validated());
            return redirect()->route('allocations.show', $allocation)
                ->with('success', 'Room allocated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Allocation $allocation)
    {
        $allocation->load(['student', 'room.building', 'assignedBy', 'payments']);
        return view('allocations.show', compact('allocation'));
    }

    public function checkOut(Request $request, Allocation $allocation)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        try {
            $this->allocationService->checkOut($allocation, $request->notes);
            return redirect()->route('allocations.show', $allocation)
                ->with('success', 'Student checked out successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}