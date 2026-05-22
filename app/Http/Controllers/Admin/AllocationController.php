<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Student;
use App\Models\Room;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function index(Request $request)
    {
        $allocations = Allocation::with(['student.user', 'room'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, function ($q, $s) {
                $q->whereHas('student', fn ($sq) =>
                    $sq->where('student_number', 'like', "%$s%")
                       ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$s%"))
                )->orWhereHas('room', fn ($r) => $r->where('room_number', 'like', "%$s%"));
            })
            ->latest()->paginate(12)->withQueryString();

        return view('admin.allocations.index', compact('allocations'));
    }

    public function create()
    {
        $students = Student::with('user')
            ->whereDoesntHave('allocations', fn ($q) => $q->where('status', 'active'))
            ->orderBy('student_number')->get();

        $rooms = Room::where('status', 'available')
            ->withCount(['activeAllocations as occupied'])->get()
            ->filter(fn ($r) => $r->occupied < $r->capacity)->values();

        return view('admin.allocations.create', compact('students', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'      => ['required', 'exists:students,id'],
            'room_id'         => ['required', 'exists:rooms,id'],
            'allocation_date' => ['required', 'date'],
            'end_date'        => ['nullable', 'date', 'after:allocation_date'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ]);

        $student = Student::findOrFail($validated['student_id']);
        if ($student->activeAllocation()->exists()) {
            return back()->withErrors(['student_id' => 'Student already has an active allocation.'])->withInput();
        }

        $room = Room::findOrFail($validated['room_id']);
        if ($room->is_full) {
            return back()->withErrors(['room_id' => 'Room is at full capacity.'])->withInput();
        }

        $allocation = Allocation::create(array_merge($validated, ['status' => 'active']));
        $this->syncRoom($room);

        ActivityLog::record('create',
            "Allocated Room {$room->room_number} to {$student->user->name}",
            'Allocation', $allocation->id);

        return redirect()->route('admin.allocations.index')
            ->with('success', "Room {$room->room_number} allocated to {$student->user->name}.");
    }

    public function show(Allocation $allocation)
    {
        $allocation->load(['student.user', 'room']);
        return view('admin.allocations.show', compact('allocation'));
    }

    public function edit(Allocation $allocation)
    {
        $allocation->load(['student.user', 'room']);
        $rooms = Room::where('status', '!=', 'maintenance')
            ->withCount(['activeAllocations as occupied'])->orderBy('room_number')->get();
        return view('admin.allocations.edit', compact('allocation', 'rooms'));
    }

    public function update(Request $request, Allocation $allocation)
    {
        $validated = $request->validate([
            'room_id'         => ['required', 'exists:rooms,id'],
            'allocation_date' => ['required', 'date'],
            'end_date'        => ['nullable', 'date', 'after:allocation_date'],
            'status'          => ['required', 'in:active,ended,cancelled'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ]);

        $oldRoomId = $allocation->room_id;
        $allocation->update($validated);
        $this->syncRoom(Room::find($oldRoomId));
        if ($oldRoomId !== (int) $validated['room_id']) {
            $this->syncRoom(Room::find($validated['room_id']));
        }

        ActivityLog::record('update', "Allocation #{$allocation->id} updated", 'Allocation', $allocation->id);

        return redirect()->route('admin.allocations.index')->with('success', 'Allocation updated.');
    }

    public function destroy(Allocation $allocation)
    {
        $room = $allocation->room;
        $allocation->delete();
        $this->syncRoom($room);
        ActivityLog::record('delete', "Allocation #{$allocation->id} deleted");

        return redirect()->route('admin.allocations.index')->with('success', 'Allocation deleted.');
    }

    private function syncRoom(Room $room): void
    {
        if ($room->status === 'maintenance') return;
        $room->update([
            'status' => $room->activeAllocations()->count() >= $room->capacity ? 'full' : 'available',
        ]);
    }
}