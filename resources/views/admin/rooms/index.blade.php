@extends('layouts.app')
@section('title', 'Rooms')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Rooms</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Rooms</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage all dormitory rooms</p>
    </div>
    <a href="{{ route('admin.rooms.create') }}" class="btn-primary">
        <i data-feather="plus" class="w-4 h-4"></i> Add Room
    </a>
</div>

<!-- Filters -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search room number or dormitory..." class="form-input pl-9">
        </div>
        <select name="dormitory_id" class="form-input w-full sm:w-48">
            <option value="">All Dormitories</option>
            @foreach($dormitories as $dorm)
            <option value="{{ $dorm->id }}" {{ request('dormitory_id') == $dorm->id ? 'selected' : '' }}>{{ $dorm->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-input w-full sm:w-40">
            <option value="">All Status</option>
            @foreach(['available','occupied','full','maintenance','reserved'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="room_type" class="form-input w-full sm:w-36">
            <option value="">All Types</option>
            @foreach(['single','double','triple','quad','suite'] as $t)
            <option value="{{ $t }}" {{ request('room_type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary">Filter</button>
        @if(request()->hasAny(['search','status','room_type','dormitory_id']))
        <a href="{{ route('admin.rooms.index') }}" class="btn-secondary">Clear</a>
        @endif
    </form>
</div>

<!-- Room Grid -->
<div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
    @forelse($rooms as $room)
    <div class="card p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white text-lg">Room {{ $room->room_number }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $room->dormitory->name }} · Floor {{ $room->floor_number }}</p>
            </div>
            <span class="{{ $room->status_badge }} capitalize">{{ $room->status }}</span>
        </div>

        <div class="space-y-2 mb-4">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 dark:text-gray-400">Type</span>
                <span class="font-medium capitalize text-gray-900 dark:text-white">{{ $room->room_type }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 dark:text-gray-400">Capacity</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $room->current_occupancy }}/{{ $room->capacity }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500 dark:text-gray-400">Rate/mo</span>
                <span class="font-semibold text-primary-600 dark:text-primary-400">₱{{ number_format($room->monthly_rate, 0) }}</span>
            </div>
        </div>

        <!-- Occupancy bar -->
        <div class="mb-4">
            <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                @php $pct = $room->capacity > 0 ? ($room->current_occupancy / $room->capacity * 100) : 0; @endphp
                <div class="h-full rounded-full transition-all {{ $pct >= 100 ? 'bg-red-500' : ($pct > 50 ? 'bg-yellow-500' : 'bg-green-500') }}"
                     style="width: {{ $pct }}%"></div>
            </div>
        </div>

        <div class="flex items-center gap-1">
            <a href="{{ route('admin.rooms.show', $room) }}" class="flex-1 btn-secondary text-xs justify-center py-1.5">
                <i data-feather="eye" class="w-3 h-3"></i> View
            </a>
            <a href="{{ route('admin.rooms.edit', $room) }}" class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                <i data-feather="edit-2" class="w-4 h-4"></i>
            </a>
            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
                  onsubmit="return confirm('Delete Room {{ $room->room_number }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <i data-feather="trash-2" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4">
        <div class="card p-12 text-center">
            <i data-feather="grid" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium">No rooms found</p>
            <a href="{{ route('admin.rooms.create') }}" class="btn-primary mt-4 inline-flex">
                <i data-feather="plus" class="w-4 h-4"></i> Add Room
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($rooms->hasPages())
<div class="card p-4">{{ $rooms->links() }}</div>
@endif
@endsection