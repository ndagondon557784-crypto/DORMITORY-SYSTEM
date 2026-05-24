<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-3xl font-bold text-navy">Rooms</h2>
                <p class="text-slate-600 mt-1">Manage dormitory rooms and availability</p>
            </div>
            <a href="{{ route('rooms.create') }}" class="flex items-center gap-2 px-6 py-3 gradient-gold text-white rounded-lg font-semibold shadow-lg-custom hover-lift transition-smooth">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Add Room
            </a>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @php
                $totalRooms = \App\Models\Room::count();
                $availableRooms = \App\Models\Room::where('status', 'available')->count();
                $occupiedRooms = \App\Models\Room::where('status', 'occupied')->count();
                $maintenanceRooms = \App\Models\Room::where('status', 'maintenance')->count();
            @endphp

            <x-card>
                <div class="text-center">
                    <p class="text-sm text-slate-600 mb-2">Total Rooms</p>
                    <p class="text-3xl font-bold text-navy">{{ $totalRooms }}</p>
                </div>
            </x-card>

            <x-card>
                <div class="text-center">
                    <p class="text-sm text-slate-600 mb-2">Available</p>
                    <p class="text-3xl font-bold text-green-600">{{ $availableRooms }}</p>
                </div>
            </x-card>

            <x-card>
                <div class="text-center">
                    <p class="text-sm text-slate-600 mb-2">Occupied</p>
                    <p class="text-3xl font-bold text-red-600">{{ $occupiedRooms }}</p>
                </div>
            </x-card>

            <x-card>
                <div class="text-center">
                    <p class="text-sm text-slate-600 mb-2">Maintenance</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $maintenanceRooms }}</p>
                </div>
            </x-card>
        </div>

        <!-- Search -->
        <x-card>
            <form method="GET" action="{{ route('rooms.index') }}" class="flex gap-2">
                <input
                    type="text"
                    name="q"
                    placeholder="Search by room number or building..."
                    value="{{ request('q') }}"
                    class="flex-1 px-4 py-3 rounded-lg border-2 border-slate-200 focus:border-royal focus:outline-none"
                />
                <button type="submit" class="px-6 py-3 gradient-gold text-white rounded-lg font-semibold hover-lift transition-smooth">
                    Search
                </button>
            </form>
        </x-card>

        <!-- Table -->
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-slate-200">
                            <th class="text-left py-4 px-6 font-bold text-navy">Room #</th>
                            <th class="text-left py-4 px-6 font-bold text-navy">Building</th>
                            <th class="text-left py-4 px-6 font-bold text-navy">Type</th>
                            <th class="text-left py-4 px-6 font-bold text-navy">Capacity</th>
                            <th class="text-left py-4 px-6 font-bold text-navy">Occupancy</th>
                            <th class="text-left py-4 px-6 font-bold text-navy">Monthly Rent</th>
                            <th class="text-left py-4 px-6 font-bold text-navy">Status</th>
                            <th class="text-center py-4 px-6 font-bold text-navy">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-smooth">
                                <td class="py-4 px-6 font-semibold text-navy">{{ $room->room_number }}</td>
                                <td class="py-4 px-6 text-slate-700">{{ $room->building->name }}</td>
                                <td class="py-4 px-6 text-slate-700 capitalize">{{ $room->type }}</td>
                                <td class="py-4 px-6 text-slate-700">{{ $room->capacity }}</td>
                                <td class="py-4 px-6 text-slate-700">{{ $room->current_occupancy }}/{{ $room->capacity }}</td>
                                <td class="py-4 px-6 font-semibold text-gold">₱{{ number_format($room->monthly_rent, 2) }}</td>
                                <td class="py-4 px-6">
                                    <span @class([
                                        'px-3 py-1 rounded-full text-xs font-bold',
                                        'bg-green-100 text-green-700' => $room->status === 'available',
                                        'bg-red-100 text-red-700' => $room->status === 'occupied',
                                        'bg-yellow-100 text-yellow-700' => $room->status === 'maintenance',
                                    ])>
                                        {{ ucfirst($room->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('rooms.show', $room) }}" class="text-royal hover:text-maroon transition-smooth" title="View">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('rooms.edit', $room) }}" class="text-blue-600 hover:text-blue-800 transition-smooth" title="Edit">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition-smooth" title="Delete">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 px-6 text-center text-slate-600">
                                    No rooms found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $rooms->links() }}
            </div>
        </x-card>
    </div>
</x-app-layout>