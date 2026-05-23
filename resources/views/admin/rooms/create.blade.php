@extends('layouts.app')
@section('title', 'Add Room')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.rooms.index') }}">Rooms</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Add Room</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Room</h1>
    </div>
    <a href="{{ route('admin.rooms.index') }}" class="btn-secondary">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Back
    </a>
</div>

<form action="{{ route('admin.rooms.store') }}" method="POST">
    @csrf
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="grid" class="w-4 h-4 text-primary-500"></i> Room Details
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Dormitory <span class="text-red-500">*</span></label>
                        <select name="dormitory_id" class="form-input @error('dormitory_id') border-red-400 @enderror">
                            <option value="">Select dormitory</option>
                            @foreach($dormitories as $dorm)
                            <option value="{{ $dorm->id }}" {{ old('dormitory_id') == $dorm->id ? 'selected' : '' }}>{{ $dorm->name }}</option>
                            @endforeach
                        </select>
                        @error('dormitory_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Room Number <span class="text-red-500">*</span></label>
                        <input type="text" name="room_number" value="{{ old('room_number') }}" class="form-input font-mono @error('room_number') border-red-400 @enderror" placeholder="101">
                        @error('room_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Room Type <span class="text-red-500">*</span></label>
                        <select name="room_type" class="form-input @error('room_type') border-red-400 @enderror">
                            <option value="">Select type</option>
                            @foreach(['single','double','triple','quad','suite'] as $t)
                            <option value="{{ $t }}" {{ old('room_type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                        @error('room_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" value="{{ old('capacity', 1) }}" min="1" max="20" class="form-input @error('capacity') border-red-400 @enderror">
                        @error('capacity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Monthly Rate (₱) <span class="text-red-500">*</span></label>
                        <input type="number" name="monthly_rate" value="{{ old('monthly_rate') }}" step="0.01" min="0" class="form-input @error('monthly_rate') border-red-400 @enderror" placeholder="3500.00">
                        @error('monthly_rate') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Floor Number <span class="text-red-500">*</span></label>
                        <input type="number" name="floor_number" value="{{ old('floor_number', 1) }}" min="1" class="form-input @error('floor_number') border-red-400 @enderror">
                        @error('floor_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="form-input @error('status') border-red-400 @enderror">
                            @foreach(['available','occupied','full','maintenance','reserved'] as $s)
                            <option value="{{ $s }}" {{ old('status','available') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Amenities</label>
                        <input type="text" name="amenities" value="{{ old('amenities') }}" class="form-input" placeholder="e.g. AC, WiFi, Private Bathroom, Wardrobe">
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Separate items with commas</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-input" placeholder="Room description...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6 bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
                <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-3 flex items-center gap-2">
                    <i data-feather="info" class="w-4 h-4"></i> Room Types Guide
                </h3>
                <ul class="space-y-1 text-xs text-blue-800 dark:text-blue-300">
                    <li><span class="font-medium">Single</span> — 1 tenant</li>
                    <li><span class="font-medium">Double</span> — 2 tenants</li>
                    <li><span class="font-medium">Triple</span> — 3 tenants</li>
                    <li><span class="font-medium">Quad</span> — 4 tenants</li>
                    <li><span class="font-medium">Suite</span> — private suite</li>
                </ul>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary justify-center">
                    <i data-feather="save" class="w-4 h-4"></i> Save Room
                </button>
                <a href="{{ route('admin.rooms.index') }}" class="btn-secondary justify-center">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection