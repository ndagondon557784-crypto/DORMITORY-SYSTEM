<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request) {
        $query = Room::withCount(['activeAllocations as occupied_count']);

        if ($request->status && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        $rooms = $query->orderBy('room_number')->get();
        return view('rooms.index', compact('rooms'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'room_number'     => 'required|string|unique:rooms',
            'floor'           => 'required|integer|min:1',
            'type'            => 'required|in:Single,Double,Triple,Quad',
            'price_per_month' => 'required|numeric|min:0',
            'amenities'       => 'nullable|array',
            'status'          => 'required|in:Available,Full,Maintenance',
        ]);

        $capacityMap = ['Single' => 1, 'Double' => 2, 'Triple' => 3, 'Quad' => 4];
        $data['capacity']  = $capacityMap[$data['type']];
        $data['occupied']  = 0;
        $data['amenities'] = json_encode($data['amenities'] ?? []);

        Room::create($data);
        return back()->with('success', 'Room added successfully.');
    }

    public function update(Request $request, Room $room) {
        $data = $request->validate([
            'room_number'     => "required|string|unique:rooms,room_number,{$room->id}",
            'floor'           => 'required|integer|min:1',
            'type'            => 'required|in:Single,Double,Triple,Quad',
            'price_per_month' => 'required|numeric|min:0',
            'amenities'       => 'nullable|array',
            'status'          => 'required|in:Available,Full,Maintenance',
        ]);

        $capacityMap = ['Single' => 1, 'Double' => 2, 'Triple' => 3, 'Quad' => 4];
        $data['capacity']  = $capacityMap[$data['type']];
        $data['amenities'] = json_encode($data['amenities'] ?? []);

        $room->update($data);
        return back()->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room) {
        if ($room->activeAllocations()->count() > 0) {
            return back()->with('error', 'Cannot delete room with active allocations.');
        }
        $room->delete();
        return back()->with('success', 'Room deleted successfully.');
    }
}