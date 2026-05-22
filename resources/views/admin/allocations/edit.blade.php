@extends('layouts.app')
@section('title','Edit Allocation')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6"><a href="{{ route('admin.allocations.index') }}" class="text-[#004D98] text-sm hover:underline">← Back</a><h1 class="text-2xl font-extrabold text-gray-800 mt-2">Edit Allocation</h1></div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5 text-sm">
            <p class="font-semibold text-blue-800">{{ $allocation->student->user->name }}</p>
            <p class="text-blue-600 text-xs">{{ $allocation->student->student_number }} · Currently in Room {{ $allocation->room->room_number }}</p>
        </div>
        <form method="POST" action="{{ route('admin.allocations.update',$allocation) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Room *</label>
                <select name="room_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                    @foreach($rooms as $r)
                    <option value="{{ $r->id }}" {{ old('room_id',$allocation->room_id)==$r->id?'selected':'' }}>Room {{ $r->room_number }} — {{ $r->building }} · {{ $r->occupied }}/{{ $r->capacity }} beds</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Allocation Date *</label>
                    <input type="date" name="allocation_date" value="{{ old('allocation_date',$allocation->allocation_date->toDateString()) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date',$allocation->end_date?->toDateString()) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Status *</label>
                <select name="status" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                    @foreach(['active','ended','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status',$allocation->status)==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">{{ old('notes',$allocation->notes) }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm">Save Changes</button>
                <a href="{{ route('admin.allocations.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition text-sm">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection