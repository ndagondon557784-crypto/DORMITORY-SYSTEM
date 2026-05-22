@extends('layouts.app')
@section('title','Allocations')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div><h1 class="text-2xl font-extrabold text-gray-800">Allocations</h1><p class="text-gray-400 text-sm">Manage all room assignments</p></div>
    <a href="{{ route('admin.allocations.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm shadow">+ Assign Room</a>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student, room..."
               class="flex-1 min-w-44 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
            <option value="">All Status</option>
            <option value="active"    {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="ended"     {{ request('status')=='ended'?'selected':'' }}>Ended</option>
            <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
        </select>
        <button type="submit" class="px-5 py-2 bg-[#004D98] text-white rounded-xl text-sm font-bold hover:bg-[#003a73] transition">Filter</button>
        <a href="{{ route('admin.allocations.index') }}" class="px-5 py-2 border border-gray-200 text-gray-500 rounded-xl text-sm hover:bg-gray-50 transition">Reset</a>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#004D98] text-white">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Student</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Room</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">From</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Until</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($allocations as $a)
                <tr class="hover:bg-blue-50/30 transition">
                    <td class="px-5 py-4"><p class="font-bold text-gray-800">{{ $a->student->user->name }}</p><p class="text-gray-400 text-xs">{{ $a->student->student_number }}</p></td>
                    <td class="px-5 py-4"><p class="font-semibold text-gray-700">Room {{ $a->room->room_number }}</p><p class="text-gray-400 text-xs">{{ $a->room->building }}</p></td>
                    <td class="px-5 py-4 text-gray-600">{{ $a->allocation_date->format('M d, Y') }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ $a->end_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $a->status==='active' ? 'bg-green-100 text-green-700' : ($a->status==='ended' ? 'bg-gray-100 text-gray-500' : 'bg-red-100 text-red-600') }}">
                            {{ ucfirst($a->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex gap-1.5">
                            <a href="{{ route('admin.allocations.show',$a) }}" class="px-3 py-1.5 bg-[#004D98] text-white text-xs rounded-lg hover:bg-[#003a73] transition font-semibold">View</a>
                            <a href="{{ route('admin.allocations.edit',$a) }}" class="px-3 py-1.5 bg-[#EDBB00] text-[#004D98] text-xs rounded-lg hover:bg-yellow-400 transition font-semibold">Edit</a>
                            <form method="POST" action="{{ route('admin.allocations.destroy',$a) }}" onsubmit="return confirm('Delete this allocation?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 bg-red-50 text-red-600 text-xs rounded-lg hover:bg-red-100 transition font-semibold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-16 text-center text-gray-400">No allocations found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($allocations->hasPages())<div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $allocations->links() }}</div>@endif
</div>
@endsection