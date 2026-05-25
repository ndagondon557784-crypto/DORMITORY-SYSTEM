@extends('layouts.app')
@section('title', 'New Allocation')
@section('page-title', 'New Allocation')
@section('breadcrumb', 'Allocations / Create')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b" style="background:linear-gradient(135deg,#004d98,#a50044)">
            <h2 class="text-lg font-bold text-white">Assign Room to Student</h2>
        </div>

        <form action="{{ route('allocations.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            @if($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200">
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $err)<li>• {{ $err }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Student *</label>
                <select name="student_id" class="form-input" required>
                    <option value="">Select a student</option>
                    @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                        {{ $student->full_name }} ({{ $student->student_id }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Room *</label>
                <select name="room_id" class="form-input" required>
                    <option value="">Select a room</option>
                    @foreach($rooms->groupBy('building.name') as $buildingName => $buildingRooms)
                    <optgroup label="{{ $buildingName }}">
                        @foreach($buildingRooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                            Room {{ $room->room_number }} — {{ ucfirst($room->room_type) }} ({{ $room->current_occupancy }}/{{ $room->capacity }}) — ₱{{ number_format($room->monthly_rate) }}/mo
                        </option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Check-in Date *</label>
                    <input type="date" name="check_in_date" value="{{ old('check_in_date', now()->toDateString()) }}" class="form-input" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Expected Check-out</label>
                    <input type="date" name="check_out_date" value="{{ old('check_out_date') }}" class="form-input">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Notes</label>
                <textarea name="notes" rows="3" class="form-input" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Allocate Room</button>
                <a href="{{ route('allocations.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection