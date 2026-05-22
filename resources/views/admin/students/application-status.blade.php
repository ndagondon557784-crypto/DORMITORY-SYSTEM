@extends('layouts.app')
@section('title','Application Status')
@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-gray-800">Application Status</h1>
        <p class="text-gray-400 text-sm mt-1">Track your room application history</p>
    </div>

    @if($allocation)
    <div class="bg-gradient-to-br from-[#004D98] to-[#003a73] text-white rounded-2xl p-6 mb-6 shadow-xl">
        <p class="text-blue-300 text-xs font-bold uppercase tracking-wider mb-1">✅ You Have an Active Room</p>
        <h2 class="text-3xl font-black">Room {{ $allocation->room->room_number }}</h2>
        <p class="text-blue-200 text-sm mt-1">{{ $allocation->room->building }} · {{ $allocation->room->floor }} Floor</p>
        <a href="{{ route('student.room') }}" class="inline-block mt-3 px-5 py-2 bg-[#EDBB00] text-[#004D98] font-bold rounded-xl text-sm hover:bg-yellow-400 transition">View Room Details →</a>
    </div>
    @endif

    @if($applications->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
        <div class="text-5xl mb-3">📋</div>
        <h2 class="font-extrabold text-gray-800 text-lg mb-2">No Applications Yet</h2>
        <p class="text-gray-500 text-sm mb-5">You haven't applied for any rooms yet.</p>
        <a href="{{ route('student.apply') }}" class="px-6 py-3 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm">Apply for a Room</a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($applications as $app)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-4 p-5">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <span class="font-extrabold text-gray-800 text-lg">Room {{ $app->room->room_number }}</span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold
                            {{ $app->status==='pending'  ? 'bg-yellow-100 text-yellow-700' :
                               ($app->status==='approved' ? 'bg-green-100 text-green-700'  : 'bg-red-100 text-red-600') }}">
                            {{ ucfirst($app->status) }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm">{{ $app->room->building }} · {{ ucfirst($app->room->type) }} · ₱{{ number_format($app->room->price_per_month,2) }}/mo</p>
                    <p class="text-gray-400 text-xs mt-1">Applied: {{ $app->created_at->format('M d, Y g:i A') }}</p>
                    @if($app->admin_notes)
                    <div class="mt-2 bg-gray-50 rounded-lg p-3 text-xs text-gray-600">
                        <span class="font-bold">Admin Note:</span> {{ $app->admin_notes }}
                    </div>
                    @endif
                </div>
                @if($app->isPending())
                <form method="POST" action="{{ route('student.apply.cancel',$app) }}"
                      onsubmit="return confirm('Cancel this application?')">
                    @csrf
                    <button class="px-4 py-2 bg-red-50 text-red-600 text-xs rounded-xl hover:bg-red-100 transition font-bold">Cancel</button>
                </form>
                @endif
            </div>
            @if($app->status==='pending')
            <div class="bg-yellow-50 px-5 py-3 border-t border-yellow-100 text-xs text-yellow-700 font-semibold">
                ⏳ Your application is under review. Please wait for admin approval.
            </div>
            @elseif($app->status==='approved')
            <div class="bg-green-50 px-5 py-3 border-t border-green-100 text-xs text-green-700 font-semibold">
                ✅ Approved! Your room has been assigned.
            </div>
            @else
            <div class="bg-red-50 px-5 py-3 border-t border-red-100 text-xs text-red-700 font-semibold">
                ❌ Application rejected. You may apply again.
            </div>
            @endif
        </div>
        @endforeach
    </div>

    @if(! $applications->firstWhere('status','pending') && ! $allocation)
    <div class="mt-5 text-center">
        <a href="{{ route('student.apply') }}" class="inline-block px-6 py-3 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm">Apply for a New Room</a>
    </div>
    @endif
    @endif
</div>
@endsection