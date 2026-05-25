<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('building')
            ->when($request->search, fn($q, $s) => $q->where('room_number', 'like', "%{$s}%"))
            ->when($request->building_id, fn($q, $b) => $q->where('building_id', $b))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->room_type, fn($q, $t) => $q->where('room_type', $t));

        $rooms     = $query->orderBy('room_number')->paginate(15)->withQueryString();
        $buildings = Building::where('is_active', true)->get();

        return view('rooms.index', compact('rooms', 'buildings'));
    }

    public function create()
    {
        $buildings = Building::where('is_active', true)->get();
        return view('rooms.create', compact('buildings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'building_id'  => 'required|exists:buildings,id',
            'room_number'  => 'required|string|max:20',
            'floor'        => 'required|integer|min:1',
            'room_type'    => 'required|in:single,double,triple,quad',
            'capacity'     => 'required|integer|min:1|max:10',
            'monthly_rate' => 'required|numeric|min:0',
            'status'       => 'required|in:available,occupied,full,maintenance',
            'amenities'    => 'nullable|string',
            'description'  => 'nullable|string',
        ]);

        $data['current_occupancy'] = 0;

        $room = Room::create($data);

        ActivityLog::log('create', "Created room: {$room->room_number}", 'Room', $room->id);

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $room->load(['building', 'activeAllocations.student', 'allocations' => fn($q) => $q->latest()->limit(10)]);
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $buildings = Building::where('is_active', true)->get();
        return view('rooms.edit', compact('room', 'buildings'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'building_id'  => 'required|exists:buildings,id',
            'room_number'  => 'required|string|max:20',
            'floor'        => 'required|integer|min:1',
            'room_type'    => 'required|in:single,double,triple,quad',
            'capacity'     => 'required|integer|min:1|max:10',
            'monthly_rate' => 'required|numeric|min:0',
            'status'       => 'required|in:available,occupied,full,maintenance',
            'amenities'    => 'nullable|string',
            'description'  => 'nullable|string',
        ]);

        $room->update($data);

        ActivityLog::log('update', "Updated room: {$room->room_number}", 'Room', $room->id);

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if ($room->current_occupancy > 0) {
            return back()->with('error', 'Cannot delete room with active occupants.');
        }

        $room->delete();

        ActivityLog::log('delete', "Deleted room: {$room->room_number}", 'Room', $room->id);

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully.');
    }

    public function available(Request $request)
    {
        $rooms = Room::with('building')
            ->where('status', 'available')
            ->when($request->building_id, fn($q, $b) => $q->where('building_id', $b))
            ->get();

        return response()->json($rooms);
    }
}