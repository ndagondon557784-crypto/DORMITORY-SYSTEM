@extends('layouts.app')
@section('title','Rooms')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div><h1 class="text-2xl font-extrabold text-gray-800">Rooms</h1><p class="text-gray-400 text-sm">Manage all dormitory rooms</p></div>
    <a href="{{ route('admin.rooms.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm shadow">+ Add Room</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search room, building..."
               class="flex-1 min-w-44 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
            <option value="">All Status</option>
            <option value="available"   {{ request('status')=='available'?'selected':'' }}>Available</option>
            <option value="full"        {{ request('status')=='full'?'selected':'' }}>Full</option>
            <option value="maintenance" {{ request('status')=='maintenance'?'selected':'' }}>Maintenance</option>
        </select>
        <select name="type" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
            <option value="">All Types</option>
            <option value="single"    {{ request('type')=='single'?'selected':'' }}>Single</option>
            <option value="double"    {{ request('type')=='double'?'selected':'' }}>Double</option>
            <option value="triple"    {{ request('type')=='triple'?'selected':'' }}>Triple</option>
            <option value="dormitory" {{ request('type')=='dormitory'?'selected':'' }}>Dormitory</option>
        </select>
        <select name="gender" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
            <option value="">All Genders</option>
            <option value="male"   {{ request('gender')=='male'?'selected':'' }}>Male</option>
            <option value="female" {{ request('gender')=='female'?'selected':'' }}>Female</option>
            <option value="mixed"  {{ request('gender')=='mixed'?'selected':'' }}>Mixed</option>
        </select>
        <button type="submit" class="px-5 py-2 bg-[#004D98] text-white rounded-xl text-sm font-bold hover:bg-[#003a73] transition">Filter</button>
        <a href="{{ route('admin.rooms.index') }}" class="px-5 py-2 border border-gray-200 text-gray-500 rounded-xl text-sm hover:bg-gray-50 transition">Reset</a>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#004D98] text-white">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Room</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Type / Gender</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Occupancy</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Price/Month</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($rooms as $room)
                @php $pct = $room->capacity > 0 ? round(($room->occupied/$room->capacity)*100) : 0; @endphp
                <tr class="hover:bg-blue-50/30 transition">
                    <td class="px-5 py-4">
                        <p class="font-extrabold text-gray-800">{{ $room->room_number }}</p>
                        <p class="text-gray-400 text-xs">{{ $room->building }} · Floor {{ $room->floor }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-700 capitalize">{{ $room->type }}</p>
                        <p class="text-gray-400 text-xs capitalize">{{ $room->gender }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-700">{{ $room->occupied }}/{{ $room->capacity }}</span>
                            <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full" style="width:{{ $pct }}%; background:{{ $pct>=100?'#A50044':($pct>=70?'#EDBB00':'#004D98') }}"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 font-semibold text-gray-700">₱{{ number_format($room->price_per_month,2) }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $room->status==='available' ? 'bg-green-100 text-green-700' : ($room->status==='full' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($room->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex gap-1.5">
                            <a href="{{ route('admin.rooms.show',$room) }}" class="px-3 py-1.5 bg-[#004D98] text-white text-xs rounded-lg hover:bg-[#003a73] transition font-semibold">View</a>
                            <a href="{{ route('admin.rooms.edit',$room) }}" class="px-3 py-1.5 bg-[#EDBB00] text-[#004D98] text-xs rounded-lg hover:bg-yellow-400 transition font-semibold">Edit</a>
                            <form method="POST" action="{{ route('admin.rooms.destroy',$room) }}" onsubmit="return confirm('Delete Room {{ $room->room_number }}?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 bg-red-50 text-red-600 text-xs rounded-lg hover:bg-red-100 transition font-semibold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-16 text-center text-gray-400">No rooms found. <a href="{{ route('admin.rooms.create') }}" class="text-[#004D98] font-semibold">Add one →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rooms->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $rooms->links() }}</div>
    @endif
</div>
@endsection