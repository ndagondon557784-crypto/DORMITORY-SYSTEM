@extends('layouts.app')
@section('title','Assign Room')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6"><a href="{{ route('admin.allocations.index') }}" class="text-[#004D98] text-sm hover:underline">← Back</a><h1 class="text-2xl font-extrabold text-gray-800 mt-2">Assign Room to Student</h1></div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @if($students->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-yellow-800 text-sm">All students are already assigned to a room. <a href="{{ route('admin.students.create') }}" class="font-bold underline">Add a new student</a> first.</div>
        @elseif($rooms->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-yellow-800 text-sm">No available rooms with open beds. <a href="{{ route('admin.rooms.create') }}" class="font-bold underline">Add a new room</a> first.</div>
        @else
        <form method="POST" action="{{ route('admin.allocations.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Student *</label>
                <select name="student_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 {{ $errors->has('student_id')?'border-red-400':'' }}">
                    <option value="">— Select a student —</option>
                    @foreach($students as $s)
                    <option value="{{ $s->id }}" {{ old('student_id')==$s->id?'selected':'' }}>{{ $s->user->name }} ({{ $s->student_number }})</option>
                    @endforeach
                </select>
                @error('student_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Room *</label>
                <select name="room_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 {{ $errors->has('room_id')?'border-red-400':'' }}">
                    <option value="">— Select a room —</option>
                    @foreach($rooms as $r)
                    <option value="{{ $r->id }}" {{ old('room_id')==$r->id?'selected':'' }}>Room {{ $r->room_number }} — {{ $r->building }} · {{ ucfirst($r->type) }} · {{ ucfirst($r->gender) }} · {{ $r->occupied }}/{{ $r->capacity }} beds · ₱{{ number_format($r->price_per_month,2) }}/mo</option>
                    @endforeach
                </select>
                @error('room_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Allocation Date *</label>
                    <input type="date" name="allocation_date" value="{{ old('allocation_date', now()->toDateString()) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">End Date (optional)</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Notes (optional)</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">{{ old('notes') }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm">Assign Room</button>
                <a href="{{ route('admin.allocations.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition text-sm">Cancel</a>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection