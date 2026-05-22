@extends('layouts.app')
@section('title','Room Detail')
@section('content')
<div class="max-w-3xl">
    <div class="mb-6"><a href="{{ route('admin.rooms.index') }}" class="text-[#004D98] text-sm hover:underline">← Back to Rooms</a></div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#004D98] px-6 py-5 text-white flex items-center justify-between">
            <div><h1 class="text-xl font-black">Room {{ $room->room_number }}</h1><p class="text-blue-200 text-sm">{{ $room->building }} — Floor {{ $room->floor }}</p></div>
            <span class="px-3 py-1 rounded-full text-sm font-bold {{ $room->status==='available' ? 'bg-green-400 text-white' : ($room->status==='full' ? 'bg-[#A50044] text-white' : 'bg-[#EDBB00] text-[#004D98]') }}">{{ ucfirst($room->status) }}</span>
        </div>
        <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-6">
            @foreach([['Type',ucfirst($room->type)],['Gender',ucfirst($room->gender)],['Capacity',$room->capacity.' beds'],['Price/Month','₱'.number_format($room->price_per_month,2)],['Building',$room->building??'N/A'],['Floor',$room->floor??'N/A']] as [$l,$v])
            <div><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">{{ $l }}</p><p class="text-gray-800 font-semibold">{{ $v }}</p></div>
            @endforeach
        </div>
        @if($room->description)<div class="px-6 pb-6"><p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Description</p><p class="text-gray-600 text-sm">{{ $room->description }}</p></div>@endif
        <div class="border-t border-gray-100 px-6 py-5">
            <h2 class="font-extrabold text-gray-800 mb-4">Current Occupants</h2>
            @php $active = $room->allocations->where('status','active'); @endphp
            @if($active->count())
            <table class="w-full text-sm"><thead class="text-xs text-gray-400 uppercase border-b border-gray-100"><tr><th class="pb-2 text-left">Student</th><th class="pb-2 text-left">From</th><th class="pb-2 text-left">Until</th><th class="pb-2 text-left">Status</th></tr></thead>
            <tbody>@foreach($active as $a)<tr class="border-b border-gray-50"><td class="py-2.5 font-semibold">{{ $a->student->user->name }}</td><td class="py-2.5 text-gray-500">{{ $a->allocation_date->format('M d, Y') }}</td><td class="py-2.5 text-gray-500">{{ $a->end_date?->format('M d, Y') ?? '—' }}</td><td class="py-2.5"><span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-bold">Active</span></td></tr>@endforeach</tbody></table>
            @else<p class="text-gray-400 text-sm">No active occupants.</p>@endif
        </div>
        <div class="border-t border-gray-100 px-6 py-4 flex gap-3">
            <a href="{{ route('admin.rooms.edit',$room) }}" class="px-5 py-2 bg-[#EDBB00] text-[#004D98] font-bold rounded-xl text-sm hover:bg-yellow-400 transition">Edit Room</a>
            <a href="{{ route('admin.allocations.create') }}" class="px-5 py-2 bg-[#004D98] text-white font-bold rounded-xl text-sm hover:bg-[#003a73] transition">Assign Student</a>
        </div>
    </div>
</div>
@endsection