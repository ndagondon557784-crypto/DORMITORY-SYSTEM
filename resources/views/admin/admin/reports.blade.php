@extends('layouts.app')
@section('title','Reports')
@section('content')
<div class="mb-8"><h1 class="text-2xl font-extrabold text-gray-800">Reports & Analytics</h1><p class="text-gray-400 text-sm mt-1">Overview of dormitory occupancy and student demographics</p></div>

<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <!-- Gender -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-extrabold text-gray-800 mb-4">Gender Distribution</h2>
        @php $total = array_sum($genderStats); @endphp
        @foreach([['Male',$genderStats['male'],'#004D98'],['Female',$genderStats['female'],'#A50044'],['Other',$genderStats['other'],'#EDBB00']] as [$label,$count,$color])
        @php $pct = $total > 0 ? round(($count/$total)*100) : 0; @endphp
        <div class="mb-3">
            <div class="flex justify-between text-xs mb-1"><span class="font-semibold text-gray-700">{{ $label }}</span><span class="text-gray-500">{{ $count }} ({{ $pct }}%)</span></div>
            <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full rounded-full" style="width:{{ $pct }}%; background:{{ $color }}"></div></div>
        </div>
        @endforeach
        <p class="text-xs text-gray-400 mt-3">Total Students: {{ $total }}</p>
    </div>

    <!-- Year Level -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-extrabold text-gray-800 mb-4">Students by Year Level</h2>
        @foreach($yearStats as $y)
        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
            <span class="text-sm font-semibold text-gray-700">Year {{ $y->year_level }}</span>
            <span class="px-3 py-1 bg-[#004D98] text-white text-xs rounded-full font-bold">{{ $y->total }}</span>
        </div>
        @endforeach
    </div>

    <!-- Top Courses -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-extrabold text-gray-800 mb-4">Top Courses</h2>
        @foreach($courseStats->take(6) as $c)
        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
            <span class="text-xs font-semibold text-gray-700 truncate max-w-36">{{ $c->course }}</span>
            <span class="px-2.5 py-1 bg-[#A50044] text-white text-xs rounded-full font-bold flex-shrink-0">{{ $c->total }}</span>
        </div>
        @endforeach
    </div>
</div>

<!-- Room Occupancy Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-extrabold text-gray-800">Room Occupancy Report</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#004D98] text-white"><tr>
                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider">Room</th>
                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider">Building</th>
                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider">Type</th>
                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider">Capacity</th>
                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider">Occupied</th>
                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider">Available</th>
                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider">Occupancy %</th>
                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($rooms as $room)
                @php $pct = $room->capacity > 0 ? round(($room->occupied/$room->capacity)*100) : 0; @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3.5 font-bold text-gray-800">{{ $room->room_number }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $room->building }}</td>
                    <td class="px-5 py-3.5 text-gray-600 capitalize">{{ $room->type }}</td>
                    <td class="px-5 py-3.5 text-gray-700 font-semibold">{{ $room->capacity }}</td>
                    <td class="px-5 py-3.5 text-gray-700 font-semibold">{{ $room->occupied }}</td>
                    <td class="px-5 py-3.5 font-bold {{ ($room->capacity - $room->occupied) > 0 ? 'text-green-600' : 'text-red-500' }}">{{ max(0,$room->capacity - $room->occupied) }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="w-20 h-1.5 bg-gray-100 rounded-full overflow-hidden"><div class="h-full rounded-full" style="width:{{ $pct }}%; background:{{ $pct>=100?'#A50044':($pct>=70?'#EDBB00':'#004D98') }}"></div></div>
                            <span class="text-xs font-bold text-gray-600">{{ $pct }}%</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5"><span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $room->status==='available' ? 'bg-green-100 text-green-700' : ($room->status==='full' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($room->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection