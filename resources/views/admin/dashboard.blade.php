@extends('layouts.app')
@section('title','Admin Dashboard')
@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-800">Dashboard</h1>
        <p class="text-gray-400 text-sm mt-1">Welcome back, <span class="text-[#004D98] font-bold">{{ auth()->user()->name }}</span> 👋</p>
    </div>
    <span class="text-xs bg-[#EDBB00] text-[#004D98] font-bold px-3 py-1.5 rounded-full">{{ now()->format('D, M d Y') }}</span>
</div>

{{-- STATS --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    @foreach([
        ['Total Students',     $stats['total_students'],      '#004D98','bg-blue-50',   'border-blue-100',   '👨‍🎓', route('admin.students.index')],
        ['Total Rooms',        $stats['total_rooms'],         '#A50044','bg-red-50',    'border-red-100',    '🏠',  route('admin.rooms.index')],
        ['Active Allocations', $stats['active_allocations'],  '#7A003C','bg-purple-50', 'border-purple-100', '📋',  route('admin.allocations.index')],
        ['Pending Applications',$stats['pending_applications'],'#b45309','bg-yellow-50','border-yellow-100', '📬',  route('admin.applications.index')],
    ] as [$label,$value,$color,$bg,$border,$icon,$link])
    <a href="{{ $link }}" class="{{ $bg }} {{ $border }} border rounded-2xl p-5 shadow-sm hover:shadow-md transition block group">
        <div class="flex items-start justify-between mb-3">
            <span class="text-3xl">{{ $icon }}</span>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
        <p class="text-3xl font-black" style="color:{{ $color }}">{{ $value }}</p>
        <p class="text-gray-500 text-sm font-medium mt-1">{{ $label }}</p>
    </a>
    @endforeach
</div>

<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['Available Rooms',     $stats['available_rooms'],    'text-green-600',  'bg-green-50 border-green-100'],
        ['Full Rooms',          $stats['full_rooms'],         'text-orange-600', 'bg-orange-50 border-orange-100'],
        ['Under Maintenance',   $stats['maintenance_rooms'],  'text-red-600',    'bg-red-50 border-red-100'],
        ['Unassigned Students', $stats['unassigned_students'],'text-amber-600',  'bg-amber-50 border-amber-100'],
    ] as [$label,$value,$tc,$bg])
    <div class="{{ $bg }} border rounded-xl p-4 text-center shadow-sm">
        <p class="text-2xl font-extrabold {{ $tc }}">{{ $value }}</p>
        <p class="text-gray-500 text-xs mt-1">{{ $label }}</p>
    </div>
    @endforeach
</div>

{{-- QUICK ACTIONS --}}
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-8">
    <a href="{{ route('admin.rooms.create') }}"        class="flex items-center gap-2 bg-[#004D98] text-white px-4 py-3 rounded-xl hover:bg-[#003a73] transition font-bold text-sm shadow">🏠 Add Room</a>
    <a href="{{ route('admin.students.create') }}"     class="flex items-center gap-2 bg-[#A50044] text-white px-4 py-3 rounded-xl hover:bg-[#7A003C] transition font-bold text-sm shadow">👤 Add Student</a>
    <a href="{{ route('admin.allocations.create') }}"  class="flex items-center gap-2 bg-[#7A003C] text-white px-4 py-3 rounded-xl hover:bg-[#5a002c] transition font-bold text-sm shadow">📋 Assign Room</a>
    <a href="{{ route('admin.applications.index') }}"  class="flex items-center gap-2 bg-amber-500 text-white px-4 py-3 rounded-xl hover:bg-amber-600 transition font-bold text-sm shadow">📬 Applications</a>
    <a href="{{ route('admin.reports') }}"             class="flex items-center gap-2 bg-[#EDBB00] text-[#004D98] px-4 py-3 rounded-xl hover:bg-yellow-400 transition font-black text-sm shadow">📊 Reports</a>
</div>

{{-- BOTTOM GRID --}}
<div class="grid lg:grid-cols-2 gap-6 mb-6">

    {{-- Pending Applications --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-extrabold text-gray-800">Pending Applications</h2>
            <a href="{{ route('admin.applications.index') }}" class="text-[#004D98] text-xs font-semibold hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($pendingApplications as $app)
            <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-gray-50 transition">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0 {{ $app->student->gender==='male' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                    {{ strtoupper(substr($app->student->user->name,0,1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate">{{ $app->student->user->name }}</p>
                    <p class="text-gray-400 text-xs">Room {{ $app->room->room_number }} · {{ $app->created_at->diffForHumans() }}</p>
                </div>
                <a href="{{ route('admin.applications.show',$app) }}" class="flex-shrink-0 px-3 py-1.5 bg-[#004D98] text-white text-xs rounded-lg font-semibold hover:bg-[#003a73] transition">Review</a>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 text-sm">No pending applications.</div>
            @endforelse
        </div>
    </div>

    {{-- Room Occupancy --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-extrabold text-gray-800">Room Occupancy</h2>
            <a href="{{ route('admin.rooms.index') }}" class="text-[#004D98] text-xs font-semibold hover:underline">Manage →</a>
        </div>
        <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
            @foreach($rooms as $room)
            @php $pct = $room->capacity > 0 ? round(($room->occupied/$room->capacity)*100) : 0; @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="font-semibold text-gray-700">{{ $room->room_number }} <span class="text-gray-400 font-normal">({{ $room->building }})</span></span>
                    <span class="text-gray-500">{{ $room->occupied }}/{{ $room->capacity }}</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all"
                         style="width:{{ $pct }}%; background:{{ $pct>=100?'#A50044':($pct>=70?'#EDBB00':'#004D98') }}"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent Allocations + Activity Log --}}
<div class="grid lg:grid-cols-2 gap-6">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-extrabold text-gray-800">Recent Allocations</h2>
            <a href="{{ route('admin.allocations.index') }}" class="text-[#004D98] text-xs font-semibold hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentAllocations as $a)
            <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-gray-50 transition">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate">{{ $a->student->user->name }}</p>
                    <p class="text-gray-400 text-xs">Room {{ $a->room->room_number }} · {{ $a->allocation_date->format('M d, Y') }}</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $a->status==='active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ ucfirst($a->status) }}</span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 text-sm">No allocations yet.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-extrabold text-gray-800">Recent Activity</h2>
            <a href="{{ route('admin.activity-logs') }}" class="text-[#004D98] text-xs font-semibold hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentLogs as $log)
            <div class="px-6 py-3 hover:bg-gray-50 transition">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                        {{ in_array($log->action,['login','register']) ? 'bg-blue-100 text-blue-700' :
                           (in_array($log->action,['create','approve']) ? 'bg-green-100 text-green-700' :
                           (in_array($log->action,['delete','reject'])  ? 'bg-red-100 text-red-600'   : 'bg-gray-100 text-gray-600')) }}">
                        {{ strtoupper($log->action) }}
                    </span>
                    <p class="text-gray-700 text-xs truncate flex-1">{{ $log->description }}</p>
                </div>
                <p class="text-gray-400 text-[10px] mt-0.5">{{ $log->user?->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 text-sm">No activity yet.</div>
            @endforelse
        </div>
    </div>

</div>

@endsection