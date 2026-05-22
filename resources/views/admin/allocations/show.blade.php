@extends('layouts.app')
@section('title','Allocation Detail')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6"><a href="{{ route('admin.allocations.index') }}" class="text-[#004D98] text-sm hover:underline">← Back</a></div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#004D98] px-6 py-5 text-white flex items-center justify-between">
            <div><h1 class="text-xl font-black">Allocation #{{ $allocation->id }}</h1><p class="text-blue-200 text-sm">Created {{ $allocation->created_at->format('M d, Y') }}</p></div>
            <span class="px-3 py-1 rounded-full text-sm font-bold {{ $allocation->status==='active' ? 'bg-green-400 text-white' : ($allocation->status==='ended' ? 'bg-gray-300 text-gray-700' : 'bg-[#A50044] text-white') }}">{{ ucfirst($allocation->status) }}</span>
        </div>
        <div class="p-6 grid grid-cols-2 gap-6">
            <div><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Student</p><p class="font-bold text-gray-800">{{ $allocation->student->user->name }}</p><p class="text-gray-400 text-xs">{{ $allocation->student->student_number }}</p></div>
            <div><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Room</p><p class="font-bold text-gray-800">Room {{ $allocation->room->room_number }}</p><p class="text-gray-400 text-xs">{{ $allocation->room->building }} · {{ ucfirst($allocation->room->type) }}</p></div>
            <div><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">From</p><p class="font-semibold text-gray-800">{{ $allocation->allocation_date->format('F d, Y') }}</p></div>
            <div><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Until</p><p class="font-semibold text-gray-800">{{ $allocation->end_date?->format('F d, Y') ?? 'Open-ended' }}</p></div>
            @if($allocation->notes)<div class="col-span-2"><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Notes</p><p class="text-gray-700 text-sm">{{ $allocation->notes }}</p></div>@endif
        </div>
        <div class="border-t border-gray-100 px-6 py-4 flex gap-3">
            <a href="{{ route('admin.allocations.edit',$allocation) }}" class="px-5 py-2 bg-[#EDBB00] text-[#004D98] font-bold rounded-xl text-sm hover:bg-yellow-400 transition">Edit</a>
            <a href="{{ route('admin.students.show',$allocation->student) }}" class="px-5 py-2 bg-[#004D98] text-white font-bold rounded-xl text-sm hover:bg-[#003a73] transition">View Student</a>
        </div>
    </div>
</div>
@endsection