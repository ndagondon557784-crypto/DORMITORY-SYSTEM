@extends('layouts.app')
@section('title', 'Edit Room')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.rooms.index') }}">Rooms</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Edit Room {{ $room->room_number }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Room {{ $room->room_number }}</h1>
    </div>
    <a href="{{ route('admin.rooms.show', $room) }}" class="btn-secondary">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Back
    </a>
</div>

<form action="{{ route('admin.rooms.update', $room) }}" method="POST">
    @csrf @method('PUT')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-feather="grid" class="w-4 h-4 text-primary-500"></i> Room Details
            </h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Dormitory <span class="text-red-500">*</span></label>
                    <select name="dormitory_id" class="form-input">
                        @foreach($dormitories as $dorm)
                        <option value="{{ $dorm->id }}" {{ old('dormitory_id', $room->dormitory_id) == $dorm->id ? 'selected' : '' }}>{{ $dorm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Room Number <span class="text-red-500">*</span></label>
                    <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}" class="form-input font-mono @error('room_number') border-red-400 @enderror">
                    @error('room_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Room Type <span class="text-red-500">*</span></label>
                    <select name="room_type" class="form-input">
                        @foreach(['single','double','triple','quad','suite'] as $t)
                        <option value="{{ $t }}" {{ old('room_type', $room->room_type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Capacity <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" max="20" class="form-input">
                </div>
                <div>
                    <label class="form-label">Monthly Rate (₱) <span class="text-red-500">*</span></label>
                    <input type="number" name="monthly_rate" value="{{ old('monthly_rate', $room->monthly_rate) }}" step="0.01" min="0" class="form-input">
                </div>
                <div>
                    <label class="form-label">Floor Number <span class="text-red-500">*</span></label>
                    <input type="number" name="floor_number" value="{{ old('floor_number', $room->floor_number) }}" min="1" class="form-input">
                </div>
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="form-input">
                        @foreach(['available','occupied','full','maintenance','reserved'] as $s)
                        <option value="{{ $s }}" {{ old('status', $room->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Amenities</label>
                    <input type="text" name="amenities" value="{{ old('amenities', $room->amenities) }}" class="form-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $room->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Current Status</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Occupancy</dt>
                        <dd class="font-medium">{{ $room->current_occupancy }}/{{ $room->capacity }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Active Tenants</dt>
                        <dd class="font-medium">{{ $room->activeAllocations->count() }}</dd>
                    </div>
                </dl>
            </div>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary justify-center">
                    <i data-feather="save" class="w-4 h-4"></i> Update Room
                </button>
                <a href="{{ route('admin.rooms.show', $room) }}" class="btn-secondary justify-center">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection