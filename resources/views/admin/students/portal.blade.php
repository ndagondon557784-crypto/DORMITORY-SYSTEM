@extends('layouts.app')
@section('title','My Profile')
@section('content')
<div class="max-w-3xl">
    <div class="mb-6"><h1 class="text-2xl font-extrabold text-gray-800">My Profile</h1></div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="bg-[#004D98] px-6 py-5 text-white flex items-center gap-4">
            <div class="w-14 h-14 rounded-full flex items-center justify-center font-black text-2xl {{ $student->gender==='male' ? 'bg-blue-300 text-blue-900' : 'bg-pink-300 text-pink-900' }}">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>
            <div>
                <h2 class="text-xl font-black">{{ auth()->user()->name }}</h2>
                <p class="text-blue-200 text-sm">{{ $student->student_number }} · {{ $student->course }}</p>
            </div>
        </div>
        <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-5">
            @foreach([['Email',auth()->user()->email],['Course',$student->course],['Year Level','Year '.$student->year_level],['Gender',ucfirst($student->gender)],['Phone',$student->phone??'—'],['Emergency',$student->emergency_contact??'—']] as [$l,$v])
            <div><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">{{ $l }}</p><p class="text-gray-800 font-semibold text-sm">{{ $v }}</p></div>
            @endforeach
        </div>
        @if($student->address)<div class="px-6 pb-5"><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Home Address</p><p class="text-gray-700 text-sm">{{ $student->address }}</p></div>@endif
    </div>

    @if($allocation)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-extrabold text-gray-800 mb-4">Current Room Assignment</h3>
        <div class="flex items-center gap-4 bg-blue-50 rounded-xl p-4">
            <div class="text-4xl">🏠</div>
            <div>
                <p class="font-black text-[#004D98] text-xl">Room {{ $allocation->room->room_number }}</p>
                <p class="text-gray-500 text-sm">{{ $allocation->room->building }} · Floor {{ $allocation->room->floor }} · {{ ucfirst($allocation->room->type) }}</p>
                <p class="text-gray-500 text-xs mt-1">{{ $allocation->allocation_date->format('M d, Y') }} → {{ $allocation->end_date?->format('M d, Y') ?? 'Open-ended' }}</p>
            </div>
            <span class="ml-auto px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Active</span>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100"><h3 class="font-extrabold text-gray-800">Allocation History</h3></div>
        @if($history && $history->count())
        <table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-100"><tr>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Room</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">From</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Until</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
        </tr></thead><tbody class="divide-y divide-gray-50">
            @foreach($history as $h)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3.5 font-semibold text-gray-800">Room {{ $h->room->room_number }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $h->allocation_date->format('M d, Y') }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $h->end_date?->format('M d, Y') ?? '—' }}</td>
                <td class="px-5 py-3.5"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $h->status==='active' ? 'bg-green-100 text-green-700' : ($h->status==='ended' ? 'bg-gray-100 text-gray-500' : 'bg-red-100 text-red-600') }}">{{ ucfirst($h->status) }}</span></td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="px-6 py-10 text-center text-gray-400 text-sm">No allocation history.</div>@endif
    </div>
</div>
@endsection