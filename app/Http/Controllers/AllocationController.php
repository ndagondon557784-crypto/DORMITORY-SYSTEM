<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function index(Request $request) {
        $query = Allocation::with(['student', 'room']);

        if ($request->search) {
            $query->whereHas('student', fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                  ->orWhereHas('room', fn($q) => $q->where('room_number', 'like', "%{$request->search}%"));
        }

        if ($request->status && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        $allocations       = $query->latest()->paginate(15)->withQueryString();
        $availableRooms    = Room::where('status', 'Available')->get();
        $unallocatedStudents = Student::where('status', 'Active')
            ->whereDoesntHave('allocation')->get();

        $stats = [
            'active'      => Allocation::where('status', 'Active')->count(),
            'transferred' => Allocation::where('status', 'Transferred')->count(),
            'vacated'     => Allocation::where('status', 'Vacated')->count(),
        ];

        return view('allocations.index', compact('allocations', 'availableRooms', 'unallocatedStudents', 'stats'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id'    => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'notes'      => 'nullable|string',
        ]);

        $room = Room::findOrFail($data['room_id']);
        if ($room->occupied >= $room->capacity) {
            return back()->with('error', 'Room is already full.');
        }

        $existing = Allocation::where('student_id', $data['student_id'])->where('status', 'Active')->first();
        if ($existing) {
            return back()->with('error', 'Student already has an active allocation.');
        }

        Allocation::create(array_merge($data, ['status' => 'Active']));
        $room->updateOccupancy();

        return back()->with('success', 'Student allocated successfully.');
    }

    public function vacate(string $id) {
        $allocation = Allocation::findOrFail($id);
        $allocation->update(['status' => 'Vacated', 'end_date' => now()->toDateString()]);
        $allocation->room->updateOccupancy();
        return back()->with('success', 'Room vacated successfully.');
    }

    public function transfer(Request $request, string $id) {
        $request->validate(['new_room_id' => 'required|exists:rooms,id']);

        $allocation = Allocation::findOrFail($id);
        $newRoom    = Room::findOrFail($request->new_room_id);

        if ($newRoom->occupied >= $newRoom->capacity) {
            return back()->with('error', 'Target room is already full.');
        }

        $allocation->update(['status' => 'Transferred', 'end_date' => now()->toDateString()]);
        $allocation->room->updateOccupancy();

        Allocation::create([
            'student_id' => $allocation->student_id,
            'room_id'    => $newRoom->id,
            'start_date' => now()->toDateString(),
            'status'     => 'Active',
            'notes'      => 'Transferred from Room ' . $allocation->room->room_number,
        ]);

        $newRoom->updateOccupancy();
        return back()->with('success', 'Student transferred successfully.');
    }

    public function destroy(Allocation $allocation) {
        $room = $allocation->room;
        $allocation->delete();
        $room->updateOccupancy();
        return back()->with('success', 'Allocation deleted.');
    }
}