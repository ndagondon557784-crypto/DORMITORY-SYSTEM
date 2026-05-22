@extends('layouts.app')
@section('title', 'Application Detail')
@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.applications.index') }}" class="text-[#004D98] text-sm hover:underline">← Back</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#004D98] px-6 py-5 text-white flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black">Application #{{ $application->id }}</h1>
                <p class="text-blue-200 text-sm">Submitted {{ $application->created_at->format('M d, Y g:i A') }}</p>
            </div>
            <span class="px-3 py-1.5 rounded-full text-sm font-bold
                {{ $application->status==='pending'  ? 'bg-yellow-400 text-yellow-900' :
                   ($application->status==='approved' ? 'bg-green-400 text-white'       : 'bg-red-400 text-white') }}">
                {{ ucfirst($application->status) }}
            </span>
        </div>

        <div class="p-6 space-y-6">
            <!-- Student -->
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">Student</p>
                <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-lg
                        {{ $application->student->gender==='male' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                        {{ strtoupper(substr($application->student->user->name,0,1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $application->student->user->name }}</p>
                        <p class="text-gray-500 text-xs">
                            {{ $application->student->student_number }} · {{ $application->student->course }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Room -->
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">Room Applied For</p>
                <div class="bg-gray-50 rounded-xl p-4 grid grid-cols-2 gap-3">
                    @foreach([
                        ['Room',     'Room '.$application->room->room_number],
                        ['Building', $application->room->building],
                        ['Type',     ucfirst($application->room->type)],
                        ['Gender',   ucfirst($application->room->gender)],
                        ['Price',    '₱'.number_format($application->room->price_per_month,2).'/mo'],
                        ['Status',   ucfirst($application->room->status)],
                    ] as [$l,$v])
                    <div>
                        <p class="text-xs text-gray-400">{{ $l }}</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ $v }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($application->reason)
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Student's Reason</p>
                <p class="text-gray-700 text-sm bg-gray-50 rounded-xl p-4">{{ $application->reason }}</p>
            </div>
            @endif

            @if($application->admin_notes)
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Admin Notes</p>
                <p class="text-gray-700 text-sm bg-gray-50 rounded-xl p-4">{{ $application->admin_notes }}</p>
            </div>
            @endif

            @if($application->reviewed_at)
            <p class="text-xs text-gray-400">
                Reviewed by <span class="font-semibold">{{ $application->reviewer?->name ?? 'System' }}</span>
                on {{ $application->reviewed_at->format('M d, Y g:i A') }}
            </p>
            @endif

            @if($application->isPending())
            <div class="border-t border-gray-100 pt-5 space-y-4">
                <!-- Approve -->
                <form method="POST" action="{{ route('admin.applications.approve',$application) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                            Admin Notes (optional)
                        </label>
                        <textarea name="admin_notes" rows="2" placeholder="Optional message to student..."
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-400 bg-gray-50 resize-none"></textarea>
                    </div>
                    <button type="submit"
                            class="w-full py-3 bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition text-sm">
                        ✅ Approve & Assign Room
                    </button>
                </form>
                <!-- Reject -->
                <form method="POST" action="{{ route('admin.applications.reject',$application) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="admin_notes" rows="2" required
                                  placeholder="Explain why this application is being rejected..."
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-400 bg-gray-50 resize-none"></textarea>
                    </div>
                    <button type="submit"
                            class="w-full py-3 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm">
                        ❌ Reject Application
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection