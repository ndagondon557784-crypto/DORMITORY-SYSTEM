@extends('layouts.app')
@section('title', 'Room ' . $room->room_number)

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.rooms.index') }}">Rooms</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Room {{ $room->room_number }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Room {{ $room->room_number }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $room->dormitory->name }} · Floor {{ $room->floor_number }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.rooms.edit', $room) }}" class="btn-primary">
            <i data-feather="edit-2" class="w-4 h-4"></i> Edit
        </a>
        <a href="{{ route('admin.rooms.index') }}" class="btn-secondary">
            <i data-feather="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900 dark:text-white">Room Info</h3>
                <span class="{{ $room->status_badge }} capitalize">{{ $room->status }}</span>
            </div>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Type</dt>
                    <dd class="font-medium capitalize">{{ $room->room_type }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Capacity</dt>
                    <dd class="font-medium">{{ $room->capacity }} person(s)</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Occupied</dt>
                    <dd class="font-medium">{{ $room->current_occupancy }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Available</dt>
                    <dd class="font-medium text-green-600">{{ $room->available_slots }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Monthly Rate</dt>
                    <dd class="font-bold text-primary-600 dark:text-primary-400">₱{{ number_format($room->monthly_rate, 2) }}</dd>
                </div>
            </dl>

            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                    <span>Occupancy</span>
                    <span>{{ $room->capacity > 0 ? round($room->current_occupancy / $room->capacity * 100) : 0 }}%</span>
                </div>
                <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    @php $pct = $room->capacity > 0 ? ($room->current_occupancy / $room->capacity * 100) : 0; @endphp
                    <div class="h-full rounded-full {{ $pct >= 100 ? 'bg-red-500' : ($pct > 50 ? 'bg-yellow-500' : 'bg-green-500') }}"
                         style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>

        @if($room->amenities)
        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Amenities</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $room->amenities) as $amenity)
                <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-xs text-gray-700 dark:text-gray-300">{{ trim($amenity) }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($room->is_available)
        <a href="{{ route('admin.allocations.create') }}?room_id={{ $room->id }}" class="btn-primary w-full justify-center">
            <i data-feather="user-plus" class="w-4 h-4"></i> Assign Student
        </a>
        @endif
    </div>

    <div class="lg:col-span-2 space-y-6">
        <!-- Current Tenants -->
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Current Tenants ({{ $room->activeAllocations->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($room->activeAllocations as $alloc)
                <div class="px-6 py-4 flex items-center gap-4">
                    <img src="{{ $alloc->student->avatar_url }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $alloc->student->full_name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $alloc->student->course }} · Year {{ $alloc->student->year_level }}</p>
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-gray-900 dark:text-white">Since {{ $alloc->check_in_date->format('M d, Y') }}</p>
                        <a href="{{ route('admin.students.show', $alloc->student) }}" class="text-xs text-primary-600 hover:underline">View profile</a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center">
                    <i data-feather="users" class="w-8 h-8 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No current tenants</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Allocation History -->
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Allocation History</h3>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead><tr><th>Student</th><th>Check-in</th><th>Check-out</th><th>Status</th></tr></thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($room->allocations as $alloc)
                        <tr>
                            <td>
                                <a href="{{ route('admin.students.show', $alloc->student) }}" class="font-medium text-primary-600 dark:text-primary-400 hover:underline">{{ $alloc->student->full_name }}</a>
                            </td>
                            <td>{{ $alloc->check_in_date->format('M d, Y') }}</td>
                            <td>{{ $alloc->check_out_date?->format('M d, Y') ?? '—' }}</td>
                            <td><span class="{{ $alloc->status_badge }}">{{ ucfirst($alloc->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-6 text-center text-sm text-gray-400 dark:text-gray-500">No allocation history</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection