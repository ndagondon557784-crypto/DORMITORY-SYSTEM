@extends('layouts.app')
@section('title', 'Room '.$room->room_number)
@section('content')

<div class="page-header">
    <div>
        <a href="{{ route('rooms.index') }}" class="text-slate-400 hover:text-slate-600 flex items-center gap-1 text-sm mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Rooms
        </a>
        <h2 class="font-display text-2xl font-700 text-slate-900">Room {{ $room->room_number }} — {{ $room->building }}</h2>
    </div>
    <div class="flex gap-3">
        @if(!$room->is_full && $room->status === 'available')
        <a href="{{ route('allocations.create') }}?room_id={{ $room->id }}" class="btn btn-success">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Allocate Student
        </a>
        @endif
        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-secondary">
            <i data-lucide="pencil" class="w-4 h-4"></i> Edit
        </a>
        <form method="POST" action="{{ route('rooms.destroy', $room) }}" onsubmit="return confirm('Delete this room?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <!-- Room Photo -->
        <div class="card overflow-hidden">
            <div class="h-56 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center relative">
                @if($room->photo)
                <img src="{{ asset('storage/'.$room->photo) }}" class="w-full h-full object-cover absolute inset-0">
                @else
                <i data-lucide="building-2" class="w-16 h-16 text-white/30"></i>
                @endif
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-display font-700 text-2xl text-slate-900">Room {{ $room->room_number }}</h3>
                        <div class="text-slate-500">{{ $room->building }}, Floor {{ $room->floor }}</div>
                    </div>
                    <span class="badge badge-{{ $room->status === 'available' ? 'success' : ($room->status === 'occupied' ? 'danger' : 'warning') }} text-sm">
                        {{ ucfirst($room->status) }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <div class="text-slate-400 text-xs font-600 uppercase">Type</div>
                        <div class="font-700 text-slate-900 capitalize mt-1">{{ $room->type }}</div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <div class="text-slate-400 text-xs font-600 uppercase">Gender</div>
                        <div class="font-700 text-slate-900 capitalize mt-1">{{ $room->gender_type }}</div>
                    </div>
                    <div class="bg-indigo-50 rounded-xl p-3">
                        <div class="text-indigo-400 text-xs font-600 uppercase">Rate</div>
                        <div class="font-700 text-indigo-700 mt-1">₱{{ number_format($room->monthly_rate, 0) }}/mo</div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <div class="text-slate-400 text-xs font-600 uppercase">Capacity</div>
                        <div class="font-700 text-slate-900 mt-1">{{ $room->occupancy }}/{{ $room->capacity }}</div>
                    </div>
                </div>

                <!-- Occupancy Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                        <span>Occupancy Rate</span>
                        <span>{{ $room->occupancy_percentage }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill {{ $room->is_full ? 'bg-red-500' : 'bg-indigo-500' }}" style="width: {{ $room->occupancy_percentage }}%"></div>
                    </div>
                </div>

                <!-- Features -->
                <div class="mt-4 flex gap-2 flex-wrap">
                    @if($room->has_wifi) <span class="flex items-center gap-1 text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-xl font-600"><i data-lucide="wifi" class="w-3 h-3"></i> WiFi</span> @endif
                    @if($room->has_aircon) <span class="flex items-center gap-1 text-xs bg-cyan-50 text-cyan-600 px-3 py-1.5 rounded-xl font-600"><i data-lucide="wind" class="w-3 h-3"></i> A/C</span> @endif
                    @if($room->has_bathroom) <span class="flex items-center gap-1 text-xs bg-green-50 text-green-600 px-3 py-1.5 rounded-xl font-600"><i data-lucide="bath" class="w-3 h-3"></i> Bath</span> @endif
                    @if($room->has_study_desk) <span class="flex items-center gap-1 text-xs bg-purple-50 text-purple-600 px-3 py-1.5 rounded-xl font-600"><i data-lucide="book-open" class="w-3 h-3"></i> Desk</span> @endif
                </div>

                @if($room->description)
                <div class="mt-4 text-sm text-slate-600 leading-relaxed">{{ $room->description }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <!-- Current Occupants -->
        <div class="card">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h4 class="font-display font-700 text-slate-900">Current Occupants</h4>
                <span class="badge badge-{{ $room->available_slots > 0 ? 'success' : 'danger' }}">
                    {{ $room->available_slots }} slot(s) available
                </span>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($room->activeAllocations as $alloc)
                <div class="flex items-center gap-4 p-4">
                    <img src="{{ $alloc->student->photo_url }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200">
                    <div class="flex-1">
                        <div class="font-600 text-slate-900">{{ $alloc->student->full_name }}</div>
                        <div class="text-sm text-slate-500">{{ $alloc->student->student_id }} • {{ $alloc->student->course }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">
                            Since {{ $alloc->check_in_date ? $alloc->check_in_date->format('M d, Y') : $alloc->start_date->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('students.show', $alloc->student) }}" class="btn btn-sm btn-secondary">
                            <i data-lucide="eye" class="w-3 h-3"></i>
                        </a>
                        <a href="{{ route('allocations.show', $alloc) }}" class="btn btn-sm btn-secondary">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-400 py-10 text-sm">
                    <i data-lucide="users" class="w-10 h-10 mx-auto mb-2 opacity-30"></i>
                    No current occupants
                </div>
                @endforelse
            </div>
        </div>

        <!-- Allocation History -->
        <div class="card">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h4 class="font-display font-700 text-slate-900">Allocation History</h4>
                <span class="badge badge-gray">{{ $room->allocations->count() }} total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left p-4 text-xs font-700 text-slate-500 uppercase">Student</th>
                            <th class="text-left p-4 text-xs font-700 text-slate-500 uppercase">Period</th>
                            <th class="text-left p-4 text-xs font-700 text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($room->allocations as $alloc)
                        <tr class="table-row">
                            <td class="p-4">
                                <div class="font-600 text-sm text-slate-900">{{ $alloc->student->full_name }}</div>
                                <div class="text-xs text-slate-500">{{ $alloc->student->student_id }}</div>
                            </td>
                            <td class="p-4 text-sm text-slate-600">
                                {{ $alloc->start_date->format('M d, Y') }}<br>
                                <span class="text-xs text-slate-400">→ {{ $alloc->end_date->format('M d, Y') }}</span>
                            </td>
                            <td class="p-4">
                                <span class="badge badge-{{ $alloc->status === 'active' ? 'success' : ($alloc->status === 'pending' ? 'warning' : 'gray') }}">
                                    {{ ucfirst($alloc->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-8 text-slate-400 text-sm">No allocation history</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 