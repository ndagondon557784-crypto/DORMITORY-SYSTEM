<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Building;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoomController extends Controller
{
    public function index(): View
    {
        $rooms = Room::with('building')
            ->paginate(15);

        return view('rooms.index', compact('rooms'));
    }

    public function create(): View
    {
        $buildings = Building::where('is_active', true)->get();
        return view('rooms.create', compact('buildings'));
    }

    public function store(StoreRoomRequest $request): RedirectResponse
    {
        Room::create($request->validated());

        return redirect()->route('rooms.index')
            ->with('success', 'Room created successfully');
    }

    public function show(Room $room): View
    {
        $room->load('building', 'allocations');
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room): View
    {
        $buildings = Building::where('is_active', true)->get();
        return view('rooms.edit', compact('room', 'buildings'));
    }

    public function update(UpdateRoomRequest $request, Room $room): RedirectResponse
    {
        $room->update($request->validated());

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room updated successfully');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully');
    }

    public function search()
    {
        $query = request()->input('query');
        $rooms = Room::where('room_number', 'like', "%{$query}%")
            ->orWhereHas('building', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->with('building')
            ->paginate(15);

        return view('rooms.index', compact('rooms'));
    }

    public function availability()
    {
        $rooms = Room::where('is_active', true)
            ->with('building')
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'building' => $room->building->name,
                    'capacity' => $room->capacity,
                    'occupancy' => $room->current_occupancy,
                    'available' => $room->available_spaces,
                    'status' => $room->status,
                    'monthly_rent' => $room->monthly_rent,
                ];
            });

        return response()->json($rooms);
    }
}