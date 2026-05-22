@extends('layouts.app')
@section('title','My Dashboard')
@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-gray-800">My Dashboard</h1>
    <p class="text-gray-400 text-sm mt-1">Welcome, <span class="text-[#004D98] font-bold">{{ auth()->user()->name }}</span> 👋</p>
</div>

{{-- Active Room Card --}}
@if($allocation)
<div class="bg-gradient-to-br from-[#004D98] to-[#003a73] text-white rounded-2xl p-6 mb-6 shadow-xl">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-blue-300 text-xs font-bold uppercase tracking-wider mb-1">Your Assigned Room</p>
            <h2 class="text-4xl font-black mb-1">Room {{ $allocation->room->room_number }}</h2>
            <p class="text-blue-200 text-sm">{{ $allocation->room->building }} · Floor {{ $allocation->room->floor }} · {{ ucfirst($allocation->room->type) }}</p>
        </div>
        <span class="px-3 py-1.5 bg-green-400 text-white text-xs font-bold rounded-full">✓ Active</span>
    </div>
    <div class="mt-5 grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach([['Type',ucfirst($allocation->room->type)],['Price','₱'.number_format($allocation->room->price_per_month,2).'/mo'],['From',$allocation->allocation_date->format('M d, Y')],['Until',$allocation->end_date?->format('M d, Y') ?? 'Open']] as [$l,$v])
        <div class="bg-white/10 rounded-xl p-3"><p class="text-blue-300 text-xs mb-0.5">{{ $l }}</p><p class="font-bold text-sm">{{ $v }}</p></div>
        @endforeach
    </div>
    <a href="{{ route('student.room') }}" class="inline-block mt-4 px-5 py-2 bg-[#EDBB00] text-[#004D98] font-bold rounded-xl text-sm hover:bg-yellow-400 transition">View Full Details →</a>
</div>

@elseif($application && $application->isPending())
<div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-6">
    <div class="flex items-center gap-4">
        <div class="text-4xl">⏳</div>
        <div class="flex-1">
            <h2 class="font-extrabold text-yellow-800 text-lg">Application Under Review</h2>
            <p class="text-yellow-700 text-sm">Your application for <strong>Room {{ $application->room->room_number }}</strong> is pending admin approval.</p>
        </div>
        <a href="{{ route('student.application.status') }}" class="px-4 py-2 bg-yellow-400 text-yellow-900 font-bold rounded-xl text-sm hover:bg-yellow-500 transition">Track →</a>
    </div>
</div>

@elseif($application && $application->isApproved())
<div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-6">
    <h2 class="font-extrabold text-green-800 text-lg mb-1">✅ Application Approved!</h2>
    <p class="text-green-700 text-sm">Room {{ $application->room->room_number }} has been assigned to you.</p>
</div>

@else
<div class="bg-white border border-dashed border-[#004D98] rounded-2xl p-6 mb-6 text-center">
    <div class="text-5xl mb-3">🏠</div>
    <h2 class="font-extrabold text-gray-800 text-xl mb-2">No Room Assigned Yet</h2>
    <p class="text-gray-500 text-sm mb-5">Apply for a dormitory room to get started.</p>
    <a href="{{ route('student.apply') }}" class="inline-block px-8 py-3 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm shadow-lg">Apply for a Room →</a>
</div>
@endif

{{-- Quick Links --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <a href="{{ route('student.portal') }}" class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all block">
        <div class="text-3xl mb-2">👤</div>
        <h3 class="font-extrabold text-gray-800 mb-1">My Profile</h3>
        <p class="text-gray-500 text-xs">View and edit your student information</p>
    </a>
    <a href="{{ route('student.room') }}" class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all block">
        <div class="text-3xl mb-2">🛏</div>
        <h3 class="font-extrabold text-gray-800 mb-1">My Room</h3>
        <p class="text-gray-500 text-xs">View your room details and amenities</p>
    </a>
    <a href="{{ route('student.application.status') }}" class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all block">
        <div class="text-3xl mb-2">📋</div>
        <h3 class="font-extrabold text-gray-800 mb-1">My Applications</h3>
        <p class="text-gray-500 text-xs">Track your room application history</p>
    </a>
</div>

@endsection