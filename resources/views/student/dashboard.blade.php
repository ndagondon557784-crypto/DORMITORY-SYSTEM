@extends('layouts.app')
@section('title', 'My Dashboard')
@section('content')

<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800">My Dashboard</h1>
    <p class="text-gray-400 text-sm mt-1">
        Welcome back, <span class="text-[#004D98] font-bold">{{ auth()->user()->name }}</span> 👋
    </p>
</div>

@if($allocation)
<div class="bg-gradient-to-br from-[#004D98] to-[#003a73] text-white rounded-2xl p-6 mb-6 shadow-xl">
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
            <p class="text-blue-300 text-xs font-bold uppercase tracking-wider mb-1">Your Assigned Room</p>
            <h2 class="text-4xl font-black mb-1">Room {{ $allocation->room->room_number }}</h2>
            <p class="text-blue-200 text-sm">
                {{ $allocation->room->building }} · Floor {{ $allocation->room->floor }} · {{ ucfirst($allocation->room->type) }}
            </p>
        </div>
        <span class="self-start px-3 py-1.5 bg-green-400 text-white text-xs font-bold rounded-full">✓ Active</span>
    </div>
    <div class="mt-5 grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach([
            ['Type',  ucfirst($allocation->room->type)],
            ['Price', '₱'.number_format($allocation->room->price_per_month,2).'/mo'],
            ['From',  $allocation->allocation_date->format('M d, Y')],
            ['Until', $allocation->end_date?->format('M d, Y') ?? 'Open-ended'],
        ] as [$l,$v])
        <div class="bg-white/10 rounded-xl p-3">
            <p class="text-blue-300 text-xs mb-0.5">{{ $l }}</p>
            <p class="font-bold text-sm">{{ $v }}</p>
        </div>
        @endforeach
    </div>
    <a href="{{ route('student.room') }}"
       class="inline-block mt-4 px-5 py-2 bg-[#EDBB00] text-[#004D98] font-bold rounded-xl text-sm hover:bg-yellow-400 transition">
        View Full Room Details →
    </a>
</div>

@elseif($application && $application->status === 'pending')
<div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="text-4xl">⏳</div>
        <div class="flex-1">
            <h2 class="font-extrabold text-yellow-800 text-lg">Application Under Review</h2>
            <p class="text-yellow-700 text-sm mt-1">
                Your application for <strong>Room {{ $application->room->room_number }}</strong>
                ({{ $application->room->building }}) is pending admin approval.
            </p>
            <p class="text-yellow-600 text-xs mt-1">Applied {{ $application->created_at->diffForHumans() }}</p>
        </div>
        <a href="{{ route('student.application.status') }}"
           class="self-start sm:self-center px-4 py-2 bg-yellow-400 text-yellow-900 font-bold rounded-xl text-sm hover:bg-yellow-500 transition whitespace-nowrap">
            Track Status →
        </a>
    </div>
</div>

@elseif($application && $application->status === 'approved')
<div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-6">
    <h2 class="font-extrabold text-green-800 text-lg mb-1">✅ Application Approved!</h2>
    <p class="text-green-700 text-sm">Your application for Room {{ $application->room->room_number }} was approved.</p>
</div>

@elseif($application && $application->status === 'rejected')
<div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-6">
    <h2 class="font-extrabold text-red-800 text-lg mb-1">❌ Application Rejected</h2>
    <p class="text-red-700 text-sm mb-3">
        @if($application->admin_notes) Reason: {{ $application->admin_notes }} @endif
    </p>
    <a href="{{ route('student.apply') }}"
       class="inline-block px-5 py-2 bg-[#A50044] text-white font-bold rounded-xl text-sm hover:bg-[#7A003C] transition">
        Apply Again →
    </a>
</div>

@else
<div class="bg-white border-2 border-dashed border-[#004D98] rounded-2xl p-8 mb-6 text-center">
    <div class="text-6xl mb-4">🏠</div>
    <h2 class="font-extrabold text-gray-800 text-xl mb-2">No Room Assigned Yet</h2>
    <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
        You have not been assigned a dormitory room. Apply now to get started.
    </p>
    <a href="{{ route('student.apply') }}"
       class="inline-block px-8 py-3 bg-[#A50044] text-white font-extrabold rounded-xl hover:bg-[#7A003C] transition text-sm shadow-lg">
        Apply for a Room →
    </a>
</div>
@endif

<!-- Quick Links -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach([
        [route('student.portal'),             '👤','My Profile',      'View your student information and history'],
        [route('student.room'),               '🛏','My Room',         'View your room details and allocation'],
        [route('student.application.status'), '📋','My Applications', 'Track your application status'],
        [route('student.apply'),              '🏠','Apply for Room',  'Browse available rooms and apply'],
        [route('student.profile.edit'),       '✏️','Edit Profile',    'Update your personal information'],
    ] as [$link,$icon,$title,$desc])
    <a href="{{ $link }}"
       class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all block group">
        <div class="text-3xl mb-3">{{ $icon }}</div>
        <h3 class="font-extrabold text-gray-800 text-lg mb-1 group-hover:text-[#004D98] transition">{{ $title }}</h3>
        <p class="text-gray-500 text-sm">{{ $desc }}</p>
    </a>
    @endforeach

    <div class="bg-gradient-to-br from-[#004D98] to-[#003a73] rounded-2xl p-5 text-white shadow-sm">
        <div class="text-3xl mb-3">🎓</div>
        <h3 class="font-extrabold text-lg mb-1">{{ auth()->user()->student->course ?? 'N/A' }}</h3>
        <p class="text-blue-200 text-sm">
            Year {{ auth()->user()->student->year_level ?? '-' }} ·
            {{ ucfirst(auth()->user()->student->gender ?? '') }}
        </p>
        <p class="text-blue-300 text-xs mt-2">
            ID: {{ auth()->user()->student->student_number ?? 'N/A' }}
        </p>
    </div>
</div>

@endsection