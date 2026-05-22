<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ActivityLog, Allocation, Notification, Room, Student};
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Allocation::with(['student', 'room.dormitory', 'allocatedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', fn($q) => $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('student_id', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $allocations = $query->latest()->paginate(15)->withQueryString();

        return view('admin.allocations.index', compact('allocations'));
    }

    public function create()
    {
        $students = Student::where('status', 'active')
            ->whereDoesntHave('allocations', fn($q) => $q->where('status', 'active'))
            ->orderBy('first_name')
            ->get();

        $rooms = Room::with('dormitory')
            ->whereIn('status', ['available', 'occupied'])
            ->where('current_occupancy', '<', \DB::raw('capacity'))
            ->get();

        return view('admin.allocations.create', compact('students', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'expected_check_out_date' => 'nullable|date|after:check_in_date',
            'deposit_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Validate student doesn't have active allocation
        $existingAllocation = Allocation::where('student_id', $validated['student_id'])
            ->where('status', 'active')
            ->exists();

        if ($existingAllocation) {
            return back()->withErrors(['student_id' => 'This student already has an active room allocation.'])->withInput();
        }

        $room = Room::findOrFail($validated['room_id']);

        if ($room->available_slots <= 0) {
            return back()->withErrors(['room_id' => 'This room is no longer available.'])->withInput();
        }

        $validated['allocated_by'] = auth()->id();

        $allocation = Allocation::create($validated);
        $room->updateOccupancy();

        $student = $allocation->student;

        ActivityLog::record('create', "Allocated room {$room->room_number} to {$student->full_name}", $allocation);

        Notification::broadcast(
            'New Room Allocation',
            "{$student->full_name} has been allocated to Room {$room->room_number}.",
            'info',
            route('admin.allocations.show', $allocation)
        );

        return redirect()->route('admin.allocations.index')
            ->with('success', "Room allocated successfully to {$student->full_name}.");
    }

    public function show(Allocation $allocation)
    {
        $allocation->load(['student', 'room.dormitory', 'allocatedBy', 'payments.receivedBy']);
        return view('admin.allocations.show', compact('allocation'));
    }

    public function edit(Allocation $allocation)
    {
        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $rooms = Room::with('dormitory')->get();
        return view('admin.allocations.edit', compact('allocation', 'students', 'rooms'));
    }

    public function update(Request $request, Allocation $allocation)
    {
        $validated = $request->validate([
            'check_in_date' => 'required|date',
            'expected_check_out_date' => 'nullable|date|after:check_in_date',
            'deposit_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,checked_out,cancelled,expired',
            'notes' => 'nullable|string',
        ]);

        $old = $allocation->toArray();
        $oldStatus = $allocation->status;

        $allocation->update($validated);

        // If status changed to checked_out or cancelled, update room
        if ($oldStatus === 'active' && in_array($validated['status'], ['checked_out', 'cancelled'])) {
            if ($validated['status'] === 'checked_out') {
                $allocation->update(['check_out_date' => now()]);
            }
            $allocation->room->updateOccupancy();
        }

        ActivityLog::record('update', "Updated allocation #{$allocation->id}", $allocation, $old, $allocation->fresh()->toArray());

        return redirect()->route('admin.allocations.show', $allocation)
            ->with('success', 'Allocation updated successfully.');
    }

    public function destroy(Allocation $allocation)
    {
        if ($allocation->status === 'active') {
            return back()->with('error', 'Cannot delete an active allocation. Please check out the student first.');
        }

        $room = $allocation->room;
        ActivityLog::record('delete', "Deleted allocation #{$allocation->id}", $allocation);
        $allocation->delete();
        $room->updateOccupancy();

        return redirect()->route('admin.allocations.index')
            ->with('success', 'Allocation deleted successfully.');
    }

    public function checkout(Request $request, Allocation $allocation)
    {
        if ($allocation->status !== 'active') {
            return back()->with('error', 'This allocation is not active.');
        }

        $allocation->update([
            'status' => 'checked_out',
            'check_out_date' => now(),
        ]);

        $allocation->room->updateOccupancy();

        ActivityLog::record('update', "Checked out {$allocation->student->full_name} from room {$allocation->room->room_number}", $allocation);

        return redirect()->route('admin.allocations.show', $allocation)
            ->with('success', 'Student checked out successfully.');
    }
}