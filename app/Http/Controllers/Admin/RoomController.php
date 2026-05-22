<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::query()
            ->when($request->search, fn ($q, $s) =>
                $q->where('room_number', 'like', "%$s%")->orWhere('building', 'like', "%$s%"))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->type,   fn ($q, $t) => $q->where('type', $t))
            ->when($request->gender, fn ($q, $g) => $q->where('gender', $g))
            ->withCount(['activeAllocations as occupied'])
            ->orderBy('building')->orderBy('room_number')
            ->paginate(12)->withQueryString();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number'     => ['required', 'string', 'max:20', 'unique:rooms'],
            'capacity'        => ['required', 'integer', 'min:1', 'max:30'],
            'type'            => ['required', 'in:single,double,triple,dormitory'],
            'gender'          => ['required', 'in:male,female,mixed'],
            'floor'           => ['nullable', 'string', 'max:50'],
            'building'        => ['nullable', 'string', 'max:100'],
            'price_per_month' => ['required', 'numeric', 'min:0'],
            'description'     => ['nullable', 'string', 'max:1000'],
            'status'          => ['required', 'in:available,full,maintenance'],
        ]);

        $room = Room::create($validated);
        ActivityLog::record('create', "Room {$room->room_number} created", 'Room', $room->id);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Room {$room->room_number} created.");
    }

    public function show(Room $room)
    {
        $room->load(['allocations' => fn ($q) => $q->with('student.user')->latest()]);
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number'     => ['required', 'string', 'max:20', 'unique:rooms,room_number,' . $room->id],
            'capacity'        => ['required', 'integer', 'min:1', 'max:30'],
            'type'            => ['required', 'in:single,double,triple,dormitory'],
            'gender'          => ['required', 'in:male,female,mixed'],
            'floor'           => ['nullable', 'string', 'max:50'],
            'building'        => ['nullable', 'string', 'max:100'],
            'price_per_month' => ['required', 'numeric', 'min:0'],
            'description'     => ['nullable', 'string', 'max:1000'],
            'status'          => ['required', 'in:available,full,maintenance'],
        ]);

        $room->update($validated);
        ActivityLog::record('update', "Room {$room->room_number} updated", 'Room', $room->id);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Room {$room->room_number} updated.");
    }

    public function destroy(Room $room)
    {
        if ($room->activeAllocations()->exists()) {
            return back()->with('error', "Cannot delete Room {$room->room_number}: active allocations exist.");
        }

        $number = $room->room_number;
        $room->delete();
        ActivityLog::record('delete', "Room $number deleted");

        return redirect()->route('admin.rooms.index')->with('success', "Room $number deleted.");
    }
}