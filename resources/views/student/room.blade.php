@extends('layouts.app')
@section('title', 'My Room')
@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-[#004D98] text-sm hover:underline">← Dashboard</a>
        <h1 class="text-2xl font-extrabold text-gray-800 mt-2">My Room</h1>
    </div>

    @if($allocation)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-br from-[#004D98] to-[#003a73] px-6 py-7 text-white text-center">
            <p class="text-blue-300 text-xs font-bold uppercase tracking-wider mb-2">Your Dormitory Room</p>
            <h2 class="text-5xl font-black mb-1">{{ $allocation->room->room_number }}</h2>
            <p class="text-blue-200">{{ $allocation->room->building }} · Floor {{ $allocation->room->floor }}</p>
            <span class="inline-block mt-3 px-4 py-1.5 bg-green-400 text-white text-xs font-bold rounded-full">✓ Currently Assigned</span>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4">
            @foreach([
                ['Room Type',   ucfirst($allocation->room->type)],
                ['Gender',      ucfirst($allocation->room->gender)],
                ['Building',    $allocation->room->building],
                ['Floor',       $allocation->room->floor ?? '—'],
                ['Price/Month', '₱'.number_format($allocation->room->price_per_month,2)],
                ['Capacity',    $allocation->room->capacity.' beds'],
            ] as [$l,$v])
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">{{ $l }}</p>
                <p class="text-gray-800 font-bold">{{ $v }}</p>
            </div>
            @endforeach
        </div>
        @if($allocation->room->description)
        <div class="px-6 pb-4">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Room Description</p>
            <p class="text-gray-600 text-sm bg-gray-50 rounded-xl p-4">{{ $allocation->room->description }}</p>
        </div>
        @endif
        <div class="px-6 pb-6">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">Allocation Period</p>
            <div class="flex items-center gap-4">
                <div class="bg-blue-50 rounded-xl p-3 flex-1 text-center">
                    <p class="text-xs text-gray-400 mb-1">From</p>
                    <p class="font-bold text-[#004D98]">{{ $allocation->allocation_date->format('M d, Y') }}</p>
                </div>
                <span class="text-gray-300 font-bold text-xl">→</span>
                <div class="bg-blue-50 rounded-xl p-3 flex-1 text-center">
                    <p class="text-xs text-gray-400 mb-1">Until</p>
                    <p class="font-bold text-[#004D98]">{{ $allocation->end_date?->format('M d, Y') ?? 'Open-ended' }}</p>
                </div>
            </div>
            @if($allocation->notes)
            <p class="text-xs text-gray-400 mt-3 bg-gray-50 rounded-xl p-3">
                <span class="font-bold">Note:</span> {{ $allocation->notes }}
            </p>
            @endif
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
        <div class="text-6xl mb-4">🏠</div>
        <h2 class="text-xl font-extrabold text-gray-800 mb-2">No Room Assigned</h2>
        <p class="text-gray-500 text-sm max-w-sm mx-auto mb-6">
            You haven't been assigned a room yet.
        </p>
        <a href="{{ route('student.apply') }}"
           class="inline-block px-8 py-3 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm shadow-lg">
            Apply for a Room →
        </a>
    </div>
    @endif
</div>

@endsection