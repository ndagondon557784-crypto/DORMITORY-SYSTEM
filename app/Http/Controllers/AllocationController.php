<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Student;
use App\Models\Room;
use App\Http\Requests\StoreAllocationRequest;
use App\Http\Requests\UpdateAllocationRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AllocationController extends Controller
{
    public function index(): View
    {
        $allocations = Allocation::with('student.user', 'room.building')
            ->paginate(15);

        return view('allocations.index', compact('allocations'));
    }

    public function create(): View
    {
        $students = Student::with('user')->get();
        $rooms = Room::where('is_active', true)
            ->where('status', 'available')
            ->with('building')
            ->get();

        return view('allocations.create', compact('students', 'rooms'));
    }

    public function store(StoreAllocationRequest $request): RedirectResponse
    {
        $allocation = Allocation::create($request->validated());

        $room = $allocation->room;
        $room->current_occupancy += 1;
        if ($room->current_occupancy >= $room->capacity) {
            $room->status = 'occupied';
        }
        $room->save();

        $student = $allocation->student;
        $student->outstanding_balance = $room->monthly_rent;
        $student->save();

        return redirect()->route('allocations.index')
            ->with('success', 'Allocation created successfully');
    }

    public function show(Allocation $allocation): View
    {
        $allocation->load('student.user', 'room.building', 'payments');
        return view('allocations.show', compact('allocation'));
    }

    public function edit(Allocation $allocation): View
    {
        $students = Student::with('user')->get();
        $rooms = Room::where('is_active', true)->with('building')->get();

        return view('allocations.edit', compact('allocation', 'students', 'rooms'));
    }

    public function update(UpdateAllocationRequest $request, Allocation $allocation): RedirectResponse
    {
        $oldRoom = $allocation->room;
        $allocation->update($request->validated());

        if ($allocation->room_id !== $oldRoom->id) {
            $oldRoom->current_occupancy -= 1;
            if ($oldRoom->current_occupancy < $oldRoom->capacity) {
                $oldRoom->status = 'available';
            }
            $oldRoom->save();

            $newRoom = $allocation->room;
            $newRoom->current_occupancy += 1;
            if ($newRoom->current_occupancy >= $newRoom->capacity) {
                $newRoom->status = 'occupied';
            }
            $newRoom->save();
        }

        return redirect()->route('allocations.show', $allocation)
            ->with('success', 'Allocation updated successfully');
    }

    public function checkout(Allocation $allocation): RedirectResponse
    {
        $allocation->status = 'completed';
        $allocation->check_out_date = now()->toDateString();
        $allocation->save();

        $room = $allocation->room;
        $room->current_occupancy -= 1;
        if ($room->current_occupancy < $room->capacity) {
            $room->status = 'available';
        }
        $room->save();

        return redirect()->route('allocations.show', $allocation)
            ->with('success', 'Student checked out successfully');
    }

    public function destroy(Allocation $allocation): RedirectResponse
    {
        $room = $allocation->room;
        $room->current_occupancy -= 1;
        if ($room->current_occupancy < $room->capacity) {
            $room->status = 'available';
        }
        $room->save();

        $allocation->delete();

        return redirect()->route('allocations.index')
            ->with('success', 'Allocation deleted successfully');
    }

    public function search()
    {
        $query = request()->input('query');
        $allocations = Allocation::whereHas('student.user', function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%");
        })
            ->orWhereHas('student', function ($q) use ($query) {
                $q->where('student_id', 'like', "%{$query}%");
            })
            ->orWhereHas('room', function ($q) use ($query) {
                $q->where('room_number', 'like', "%{$query}%");
            })
            ->with('student.user', 'room.building')
            ->paginate(15);

        return view('allocations.index', compact('allocations'));
    }
}