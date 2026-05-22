@extends('layouts.app')
@section('title','Student Detail')
@section('content')
<div class="max-w-3xl">
    <div class="mb-6"><a href="{{ route('admin.students.index') }}" class="text-[#004D98] text-sm hover:underline">← Back</a></div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#004D98] px-6 py-5 text-white flex items-center gap-4">
            <div class="w-14 h-14 rounded-full flex items-center justify-center font-black text-2xl {{ $student->gender==='male' ? 'bg-blue-300 text-blue-900' : 'bg-pink-300 text-pink-900' }}">
                {{ strtoupper(substr($student->user->name,0,1)) }}
            </div>
            <div>
                <h1 class="text-xl font-black">{{ $student->user->name }}</h1>
                <p class="text-blue-200 text-sm">{{ $student->student_number }} · {{ $student->course }}</p>
            </div>
        </div>
        <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-6">
            @foreach([['Email',$student->user->email],['Course',$student->course],['Year','Year '.$student->year_level],['Gender',ucfirst($student->gender)],['Phone',$student->phone??'—'],['Emergency',$student->emergency_contact??'—']] as [$l,$v])
            <div><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">{{ $l }}</p><p class="text-gray-800 font-semibold text-sm">{{ $v }}</p></div>
            @endforeach
        </div>
        @if($student->address)<div class="px-6 pb-4"><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Address</p><p class="text-gray-700 text-sm">{{ $student->address }}</p></div>@endif
        <div class="border-t border-gray-100 px-6 py-5">
            <h2 class="font-extrabold text-gray-800 mb-4">Allocation History</h2>
            @if($student->allocations->count())
            <table class="w-full text-sm"><thead class="text-xs text-gray-400 uppercase border-b border-gray-100"><tr><th class="pb-2 text-left">Room</th><th class="pb-2 text-left">From</th><th class="pb-2 text-left">Until</th><th class="pb-2 text-left">Status</th></tr></thead>
            <tbody>@foreach($student->allocations as $a)
            <tr class="border-b border-gray-50"><td class="py-2.5 font-semibold">Room {{ $a->room->room_number }}</td><td class="py-2.5 text-gray-500">{{ $a->allocation_date->format('M d, Y') }}</td><td class="py-2.5 text-gray-500">{{ $a->end_date?->format('M d, Y') ?? '—' }}</td>
            <td class="py-2.5"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $a->status==='active' ? 'bg-green-100 text-green-700' : ($a->status==='ended' ? 'bg-gray-100 text-gray-500' : 'bg-red-100 text-red-600') }}">{{ ucfirst($a->status) }}</span></td></tr>
            @endforeach</tbody></table>
            @else<p class="text-gray-400 text-sm">No allocation history.</p>@endif
        </div>
        <div class="border-t border-gray-100 px-6 py-4 flex gap-3">
            <a href="{{ route('admin.students.edit',$student) }}" class="px-5 py-2 bg-[#EDBB00] text-[#004D98] font-bold rounded-xl text-sm hover:bg-yellow-400 transition">Edit</a>
            <a href="{{ route('admin.allocations.create') }}" class="px-5 py-2 bg-[#004D98] text-white font-bold rounded-xl text-sm hover:bg-[#003a73] transition">Assign Room</a>
        </div>
    </div>
</div>
@endsection