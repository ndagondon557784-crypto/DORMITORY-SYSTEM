@extends('layouts.app')
@section('title', $dormitory->name)

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.dormitories.index') }}">Dormitories</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>{{ $dormitory->name }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $dormitory->name }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">{{ $dormitory->code }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.rooms.create') }}?dormitory_id={{ $dormitory->id }}" class="btn-primary">
            <i data-feather="plus" class="w-4 h-4"></i> Add Room
        </a>
        <a href="{{ route('admin.dormitories.edit', $dormitory) }}" class="btn-secondary">
            <i data-feather="edit-2" class="w-4 h-4"></i> Edit
        </a>
        <a href="{{ route('admin.dormitories.index') }}" class="btn-secondary">
            <i data-feather="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid sm:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['Total Rooms', $dormitory->rooms->count(), 'grid', 'blue'],
        ['Available', $dormitory->available_rooms_count, 'check-circle', 'green'],
        ['Occupied', $dormitory->occupied_rooms_count, 'users', 'yellow'],
        ['Capacity', $dormitory->total_capacity, 'maximize', 'purple'],
    ] as [$label, $value, $icon, $color])
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="{{ $icon }}" class="w-5 h-5 text-{{ $color }}-600 dark:text-{{ $color }}-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $label }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Info sidebar -->
    <div class="space-y-6">
        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Dormitory Info</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2">
                    <i data-feather="map-pin" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                    <span class="text-gray-700 dark:text-gray-300">{{ $dormitory->address }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-feather="phone" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                    <span class="text-gray-700 dark:text-gray-300">{{ $dormitory->contact_number }}</span>
                </div>
                @if($dormitory->email)
                <div class="flex items-center gap-2">
                    <i data-feather="mail" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                    <span class="text-gray-700 dark:text-gray-300">{{ $dormitory->email }}</span>
                </div>
                @endif
                <div class="flex items-center gap-2">
                    <i data-feather="toggle-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                    <span class="{{ $dormitory->is_active ? 'text-green-600' : 'text-gray-400' }} font-medium">{{ $dormitory->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
            @if($dormitory->description)
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $dormitory->description }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Rooms list -->
    <div class="lg:col-span-2">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">Rooms ({{ $dormitory->rooms->count() }})</h3>
                <a href="{{ route('admin.rooms.create') }}?dormitory_id={{ $dormitory->id }}" class="btn-primary text-xs py-1.5">
                    <i data-feather="plus" class="w-3 h-3"></i> Add Room
                </a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Room No.</th>
                            <th>Type</th>
                            <th>Floor</th>
                            <th>Occupancy</th>
                            <th>Rate/mo</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($dormitory->rooms->sortBy('room_number') as $room)
                        <tr>
                            <td class="font-mono font-semibold">{{ $room->room_number }}</td>
                            <td class="capitalize">{{ $room->room_type }}</td>
                            <td>{{ $room->floor_number }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium">{{ $room->current_occupancy }}/{{ $room->capacity }}</span>
                                    <div class="flex-1 max-w-16 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                        @php $pct = $room->capacity > 0 ? $room->current_occupancy / $room->capacity * 100 : 0; @endphp
                                        <div class="h-full rounded-full {{ $pct >= 100 ? 'bg-red-500' : ($pct > 50 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width:{{ $pct }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="font-semibold text-primary-600 dark:text-primary-400">₱{{ number_format($room->monthly_rate, 0) }}</td>
                            <td><span class="{{ $room->status_badge }} capitalize text-xs">{{ $room->status }}</span></td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.rooms.show', $room) }}" class="p-1 rounded text-gray-500 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                                        <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <a href="{{ route('admin.rooms.edit', $room) }}" class="p-1 rounded text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                        <i data-feather="edit-2" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">No rooms added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection