<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ActivityLog, Dormitory, Room};
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('dormitory');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                    ->orWhereHas('dormitory', fn($d) => $d->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('room_type')) {
            $query->where('room_type', $request->room_type);
        }

        if ($request->filled('dormitory_id')) {
            $query->where('dormitory_id', $request->dormitory_id);
        }

        $rooms = $query->orderBy('dormitory_id')->orderBy('room_number')->paginate(15)->withQueryString();
        $dormitories = Dormitory::where('is_active', true)->get();

        return view('admin.rooms.index', compact('rooms', 'dormitories'));
    }

    public function create()
    {
        $dormitories = Dormitory::where('is_active', true)->get();
        return view('admin.rooms.create', compact('dormitories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dormitory_id' => 'required|exists:dormitories,id',
            'room_number' => 'required|string|max:20',
            'room_type' => 'required|in:single,double,triple,quad,suite',
            'capacity' => 'required|integer|min:1|max:20',
            'monthly_rate' => 'required|numeric|min:0',
            'floor_number' => 'required|integer|min:1|max:50',
            'status' => 'required|in:available,occupied,full,maintenance,reserved',
            'amenities' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Check uniqueness within dormitory
        $exists = Room::where('dormitory_id', $validated['dormitory_id'])
            ->where('room_number', $validated['room_number'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['room_number' => 'Room number already exists in this dormitory.'])->withInput();
        }

        $room = Room::create($validated);
        ActivityLog::record('create', "Created room: {$room->room_number}", $room);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Room {$room->room_number} created successfully.");
    }

    public function show(Room $room)
    {
        $room->load(['dormitory', 'activeAllocations.student', 'allocations.student']);
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $dormitories = Dormitory::where('is_active', true)->get();
        return view('admin.rooms.edit', compact('room', 'dormitories'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'dormitory_id' => 'required|exists:dormitories,id',
            'room_number' => 'required|string|max:20',
            'room_type' => 'required|in:single,double,triple,quad,suite',
            'capacity' => 'required|integer|min:1|max:20',
            'monthly_rate' => 'required|numeric|min:0',
            'floor_number' => 'required|integer|min:1|max:50',
            'status' => 'required|in:available,occupied,full,maintenance,reserved',
            'amenities' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $exists = Room::where('dormitory_id', $validated['dormitory_id'])
            ->where('room_number', $validated['room_number'])
            ->where('id', '!=', $room->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['room_number' => 'Room number already exists in this dormitory.'])->withInput();
        }

        $old = $room->toArray();
        $room->update($validated);
        ActivityLog::record('update', "Updated room: {$room->room_number}", $room, $old, $room->fresh()->toArray());

        return redirect()->route('admin.rooms.show', $room)
            ->with('success', "Room {$room->room_number} updated successfully.");
    }

    public function destroy(Room $room)
    {
        if ($room->activeAllocations()->count() > 0) {
            return back()->with('error', 'Cannot delete a room with active allocations.');
        }

        $number = $room->room_number;
        ActivityLog::record('delete', "Deleted room: {$number}", $room);
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', "Room {$number} deleted successfully.");
    }

    public function checkAvailability(Request $request)
    {
        $rooms = Room::with('dormitory')
            ->whereIn('status', ['available', 'occupied'])
            ->where('current_occupancy', '<', \DB::raw('capacity'))
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'label' => "{$r->dormitory->name} - Room {$r->room_number} ({$r->available_slots} slot/s left)",
                'available_slots' => $r->available_slots,
                'monthly_rate' => $r->monthly_rate,
            ]);

        return response()->json($rooms);
    }
}